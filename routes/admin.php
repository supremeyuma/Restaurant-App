<?php

use App\Http\Controllers\Admin\CategoryController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
});
