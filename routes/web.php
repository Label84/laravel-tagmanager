<?php

use Illuminate\Support\Facades\Route;
use Label84\TagManager\Http\Controllers\StoreMeasurementProtocolClientIdController;

Route::post('/tagmanager/store-measurement-protocol-client-id', StoreMeasurementProtocolClientIdController::class)
    ->middleware('web')
    ->name('tagmanager.store-measurement-protocol-client-id');
