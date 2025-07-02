<?php

use Illuminate\Support\Facades\Route;
use admin\faqs\Controllers\FaqManagerController;

Route::name('admin.')->middleware(['web','auth:admin'])->group(function () {  
    Route::middleware('auth:admin')->group(function () {
        Route::resource('faqs', FaqManagerController::class);
        Route::post('faqs/updateStatus', [FaqManagerController::class, 'updateStatus'])->name('faqs.updateStatus');
    });
});
