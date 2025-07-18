<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // For logging
use App\Models\Order; // Assuming your Order model is in App\Models

class PaystackWebhookController extends Controller
{
    /**
     * Handle incoming Paystack webhook notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        // Get the Paystack secret key from your environment variables
        $paystackSecretKey = env('PAYSTACK_SECRET_KEY');

        // Get the signature from the request header
        $signature = $request->header('x-paystack-signature');

        // Get the raw request body
        $payload = $request->getContent();

        // 1. Verify the webhook signature
        // This is CRUCIAL for security to ensure the request genuinely came from Paystack
        if (!$this->verifyPaystackSignature($payload, $signature, $paystackSecretKey)) {
            Log::warning('Paystack Webhook: Invalid signature received.', [
                'ip' => $request->ip(),
                'signature' => $signature,
                'payload_hash' => hash_hmac('sha512', $payload, 'dummy_key_for_log_comparison') // Use a dummy key for logging hash
            ]);
            return response()->json(['message' => 'Invalid signature'], 401); // Unauthorized
        }

        // Parse the JSON payload
        $event = json_decode($payload, true);

        // Log the incoming webhook event for debugging purposes
        Log::info('Paystack Webhook: Received event.', ['event_type' => $event['event'] ?? 'unknown', 'reference' => $event['data']['reference'] ?? 'N/A']);

        // 2. Process the event based on its type
        // We are primarily interested in 'charge.success' for payment confirmation
        switch ($event['event']) {
            case 'charge.success':
                $data = $event['data'];
                $reference = $data['reference'];
                $status = $data['status'];
                $amount_paid_kobo = $data['amount']; // Amount in kobo/cent
                $currency = $data['currency'];
                $paystack_transaction_id = $data['id']; // Paystack's unique transaction ID

                // Find the corresponding order in your database using the reference
                $order = Order::where('reference', $reference)->first();

                if ($order) {
                    // Check if the order is not already marked as paid
                    // and if the Paystack status is 'success'
                    if ($order->status !== 'paid' && $status === 'success') {
                        // Optional: Verify amount to prevent tampering (though Paystack handles this internally)
                        // if ($order->amount * 100 !== $amount_paid_kobo) {
                        //     Log::error('Paystack Webhook: Amount mismatch for order.', ['order_id' => $order->id, 'expected_amount' => $order->amount * 100, 'actual_amount' => $amount_paid_kobo]);
                        //     // You might want to flag this for manual review or refund
                        //     return response()->json(['status' => 'success'], 200); // Still return 200 to Paystack
                        // }

                        $order->status = 'paid';
                        $order->paystack_ref = $reference; // Store the Paystack reference
                        $order->paystack_transaction_id = $paystack_transaction_id; // Store Paystack's internal transaction ID
                        $order->save();

                        Log::info('Paystack Webhook: Order marked as paid.', ['order_id' => $order->id, 'reference' => $reference]);

                        // Clear the user's cart (if applicable and you can identify the user via order)
                        // This might be better handled in your OrderController's confirm method or a dedicated service
                        // if (session()->has('cart')) {
                        //     session()->forget('cart');
                        // }

                        // You might also want to:
                        // - Send a confirmation email to the customer
                        // - Update inventory
                        // - Trigger other post-payment processes
                    } else {
                        Log::info('Paystack Webhook: Order already paid or status not success.', ['order_id' => $order->id, 'current_status' => $order->status, 'paystack_status' => $status]);
                    }
                } else {
                    Log::error('Paystack Webhook: Order not found for reference.', ['reference' => $reference]);
                }
                break;

            // Add other event types you want to handle (e.g., 'charge.failed', 'transfer.success', etc.)
            // case 'charge.failed':
            //     // Handle failed charges
            //     break;

            default:
                // Log unhandled events
                Log::info('Paystack Webhook: Unhandled event type.', ['event_type' => $event['event']]);
                break;
        }

        // 3. Always return a 200 OK response to Paystack
        // This tells Paystack that you successfully received and processed the webhook.
        // If you don't return 200, Paystack will keep retrying the webhook.
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Verify the Paystack webhook signature.
     *
     * @param string $payload The raw request body.
     * @param string $signature The X-Paystack-Signature header value.
     * @param string $secretKey Your Paystack secret key.
     * @return bool
     */
    protected function verifyPaystackSignature(string $payload, ?string $signature, string $secretKey): bool
    {
        if (empty($signature)) {
            return false;
        }

        // Calculate the expected signature
        $expectedSignature = hash_hmac('sha512', $payload, $secretKey);

        // Compare the expected signature with the received signature
        return hash_equals($expectedSignature, $signature);
    }
}