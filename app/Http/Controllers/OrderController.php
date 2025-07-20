<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use GuzzleHttp\Client; // Import Guzzle Client
use Carbon\Carbon;
use App\Models\Setting;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect('/menu')->with('error', 'Your cart is empty.');
        }
        $deliveryFee = Setting('delivery_fee', 2500); // fallback to 500 if not set
        $deliveryEnabled = setting('delivery_enabled');

        return view('order.checkout', compact('cart', 'deliveryFee', 'deliveryEnabled'));
    }

    public function pay(Request $request)
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['qty'] * $i['price']);

        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Your cart is empty.');
        }

        // Validate inputs
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'required_if:address,!=,null|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $isDelivery = $request->has('address') && $request->filled('address');

        // Create the order first
        $order = Order::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email, // Assuming you add email to your checkout form and Order model
            'total' => $total,
            'status' => 'pending', // Status is pending until verified by Paystack
            'pickup_code' => strtoupper(Str::random(6)),
            'address' => $validated['address'] ?? null,
            'is_delivery' => $isDelivery,
            'reference' => 'MOR-' . Str::uuid(), // Generate a unique reference early
        ]);

        foreach ($cart as $c) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $c['id'],
                'item_name' => $c['name'],
                'qty' => $c['qty'],
                'unit_price' => $c['price'],
            ]);
        }

        session()->put('last_order_id', $order->id);

        // --- Paystack Integration (Corrected) ---
        $client = new Client();
        $paystackSecretKey = env('PAYSTACK_SECRET_KEY');
        $paystackCallbackUrl = env('PAYSTACK_CALLBACK_URL'); // Your return URL after payment

        try {
            $response = $client->post('https://api.paystack.co/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $paystackSecretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'email' => $request->email, // Use the user's email from the request
                    'amount' => $total * 100, // Amount in kobo/cent
                    'reference' => $order->reference, // Use the generated unique reference
                    'callback_url' => $paystackCallbackUrl . '?order_id=' . $order->id, // Pass order ID for context
                    'metadata' => [ // Optional: useful for additional context
                        'order_id' => $order->id,
                        'customer_name' => $request->name,
                    ],
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] && isset($body['data']['authorization_url'])) {
                // Redirect user to Paystack's authorization URL
                return redirect()->away($body['data']['authorization_url']);
            } else {
                // Handle error from Paystack API
                $order->status = 'failed';
                $order->save();
                return redirect()->back()->with('error', 'Payment initialization failed: ' . ($body['message'] ?? 'Unknown error'));
            }

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle HTTP request errors (e.g., network issues, invalid API key)
            $order->status = 'failed';
            $order->save();
            return redirect()->back()->with('error', 'Could not connect to Paystack: ' . $e->getMessage());
        }
    }

    public function confirm(Request $request)
    {
        // This method will now primarily verify the transaction after the user is redirected back
        // from Paystack. The most robust way is still a Paystack webhook.

        $orderId = $request->query('order_id') ?? session('last_order_id');
        $reference = $request->query('trxref') ?? $request->query('reference'); // Paystack sends trxref or reference

        if (!$orderId || !$reference) {
            return redirect('/menu')->with('error', 'Payment confirmation failed: Missing order information.');
        }

        $order = Order::findOrFail($orderId);

        // Prevent multiple updates if already paid
        if ($order->status === 'paid') {
            session()->forget('cart');
            return view('order.confirm', compact('order'))->with('info', 'This order has already been paid for.');
        }

        // --- Verify transaction with Paystack API ---
        $client = new Client();
        $paystackSecretKey = env('PAYSTACK_SECRET_KEY');

        try {
            $response = $client->get("https://api.paystack.co/transaction/verify/{$reference}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $paystackSecretKey,
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] && $body['data']['status'] === 'success') {
                // Payment was successful
                $order->status = 'paid';
                $order->paystack_ref = $reference; // Store the Paystack reference
                $order->save();

                session()->forget('cart');
                return view('order.confirm', compact('order'))->with('success', 'Payment successful!');
            } else {
                // Payment not successful or verification failed
                $order->status = 'failed'; // Or 'cancelled' based on Paystack status
                $order->save();
                return redirect('/menu')->with('error', 'Payment verification failed or payment was not successful.');
            }

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle HTTP request errors during verification
            $order->status = 'failed'; // Or 'error'
            $order->save();
            return redirect('/menu')->with('error', 'Error verifying payment with Paystack: ' . $e->getMessage());
        }
    }

    // You should also set up a webhook to receive real-time updates from Paystack
    // This is the most reliable way to confirm payments.
    // public function handlePaystackWebhook(Request $request) { ... }
}