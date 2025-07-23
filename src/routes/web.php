<?php

use Illuminate\Support\Facades\Route;
use admin\faqs\Controllers\FaqManagerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
    Route::resource('faqs', FaqManagerController::class);
    Route::post('faqs/updateStatus', [FaqManagerController::class, 'updateStatus'])->name('faqs.updateStatus');
});
