<?php

use Illuminate\Support\Facades\Route;
use ThirtyBitTech\Autoreply\Http\Controllers\GetFormFieldsController;

Route::name('autoreply.')->prefix('autoreply')->group(function () {
    Route::get('form-fields/{form}', [GetFormFieldsController::class, '__invoke'])->name('form-fields');
});
