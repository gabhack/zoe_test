<?php

use App\Http\Controllers\SecurityPriceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/sync-prices/{type}', [SecurityPriceController::class, 'syncPrices']);
