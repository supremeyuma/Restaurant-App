<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderScanController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::post('/cart/save', [MenuController::class, 'saveCart'])->name('cart.save');

Route::get('/cart', [OrderController::class, 'checkout'])->name('cart.checkout');
Route::post('/pay', [OrderController::class, 'pay'])->name('pay');
Route::get('/confirm', [OrderController::class, 'confirm'])->name('pay.confirm');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/scan', [OrderScanController::class, 'scanner'])->name('admin.scan');
    Route::get('/scan/result/{code}', [OrderScanController::class, 'result'])->name('admin.scan.result');
    Route::post('/scan/complete', [OrderScanController::class, 'complete'])->name('admin.scan.complete');
});

require __DIR__.'/auth.php';
