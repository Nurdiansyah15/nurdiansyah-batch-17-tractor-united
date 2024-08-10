<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\AuthController;
use App\Utils\ResponseFormator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//public
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

//authenticated Bearer token
Route::middleware(['nurd.jwt'])->group(function () {
    Route::get('/category-products', [CategoryController::class, 'findAll']);
    Route::get('/category-products/{id}', [CategoryController::class, 'findById']);
    Route::post('/category-products', [CategoryController::class, 'create']);
    Route::put('/category-products/{id}', [CategoryController::class, 'update']);
    Route::delete('/category-products/{id}', [CategoryController::class, 'delete']);

    Route::get('/products', [ProductController::class, 'findAll']);
    Route::get('/products/{id}', [ProductController::class, 'findById']);
    Route::post('/products', [ProductController::class, 'create']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'delete']);

    Route::get('/transactions', [TransactionController::class, 'findAll']);
    Route::get('/transactions/{id}', [TransactionController::class, 'findById']);
    Route::post('/transactions', [TransactionController::class, 'create']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'delete']);

    Route::get('/user/transactions', [TransactionController::class, 'findAllUserTransactions']);
    Route::get('/user/transactions/{id}', [TransactionController::class, 'findUserTransactionById']);
    Route::post('/user/transactions', [TransactionController::class, 'createUserTransaction']);
    Route::put('/user/transactions/{id}', [TransactionController::class, 'updateUserTransaction']);
    Route::delete('/user/transactions/{id}', [TransactionController::class, 'deleteUserTransaction']);
});

Route::fallback(function () {
    return ResponseFormator::create(404, "Not Found");
});
