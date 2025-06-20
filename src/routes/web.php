<?php

use Illuminate\Support\Facades\Route;
use admin\faqs\Controllers\FaqController;

Route::name('admin.')->middleware(['web','auth:admin'])->group(function () {  
    Route::middleware('auth:admin')->group(function () {
        Route::resource('faqs', FaqController::class);
        Route::post('faqs/updateStatus', [FaqController::class, 'updateStatus'])->name('faqs.updateStatus');
    });
});
