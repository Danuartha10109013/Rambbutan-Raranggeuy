<?php

use illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MindtranceController;


Route::match(['get', 'post'], '/midtrans-callback/{order_id}', [MindtranceController::class, 'callback'])->name('midtrance-callback');
