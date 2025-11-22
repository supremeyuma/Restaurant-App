<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderScanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Models\Setting;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\OrderTrackingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contact', function () {
    $settings = Setting::firstOrFail();
    return view('contact', compact('settings'));
})->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/pre-order', [MenuController::class, 'preOrder'])->name('menu.pre-order');
Route::post('/cart/save', [MenuController::class, 'saveCart'])->name('cart.save');

Route::get('/pre-order/cart', [OrderController::class, 'checkout'])->name('cart.checkout');
Route::post('/pay', [OrderController::class, 'pay'])->name('pay');
Route::get('/confirm', [OrderController::class, 'confirm'])->name('pay.confirm');
/*Route::post('/cart/save', function(Request $request) {
    session(['cart' => $request->cart]);
    return response()->json(['status' => 'ok']);
});*/

//Admin Routes
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('items', ItemController::class);
    Route::resource('categories', CategoryController::class);

    //scan route
    Route::get('/scan', [OrderScanController::class, 'scanner'])->name('scan');
    Route::get('/scan/result/{code}', [OrderScanController::class, 'result'])->name('scan.result');
    Route::post('/scan/complete', [OrderScanController::class, 'complete'])->name('scan.complete');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/export-pdf', [AdminOrderController::class, 'exportPdf'])->name('orders.export_pdf');

    //Settings
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');



});

Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('track.index');
Route::post('/track-order', [OrderTrackingController::class, 'track'])->name('track.check');


require __DIR__.'/auth.php';
