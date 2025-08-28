<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/register', [AuthController::class, 'authenticate']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/', [ItemController::class, 'index']);
Route::post('/', [ItemController::class, 'serchItem']);

Route::get('/item/{item}', [ItemController::class, 'bindItem']);

Route::middleware('auth')->group(
    function () {
        Route::get('/mypage', [UserController::class, 'mypage']);
        Route::get('/mypage/profile', [UserController::class, 'profile']);
        Route::post('/mypage/profile', [UserController::class, 'storeProfile']);

        Route::get('/sell', [ItemController::class, 'sell']);
        Route::post('/sell', [ItemController::class, 'storeSell']);

        Route::post('/item/{item}', [ItemController::class, 'storeComent']);
        Route::put('/item/good/{item}', [ItemController::class, 'storeLikeButton']);

        Route::get('/purchase/{item}', [ItemController::class, 'bindPurchase'])->name('purchase.show');
        Route::post('/purchase/{item}', [ItemController::class, 'storePurchase']);

        Route::get('/purchase/address/{item}', [ItemController::class, 'bindAddress']);
        Route::post('/purchase/address/{item}', [ItemController::class, 'storeAddress']);
    }
);
