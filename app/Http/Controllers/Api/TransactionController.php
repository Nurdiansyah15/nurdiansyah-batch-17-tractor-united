<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Utils\ResponseFormator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{

    public function findAll()
    {
        $transactions = Transaction::all();
        return ResponseFormator::create(200, "Success", $transactions);
    }

    public function findById($id)
    {
        $transaction = Transaction::where('id', $id)->first();
        if (!$transaction) {
            return ResponseFormator::create(404, "Not Found");
        }
        return ResponseFormator::create(200, "Success", $transaction);
    }
    public function create(Request $request)
    {
        try {
            $fields = $request->validate([
                'product_id' => 'numeric|required',
                'user_id' => 'numeric|required',
                'quantity' => 'numeric|required'
            ]);
            $product = Product::where('id', $fields['product_id'])->first();
            if (!$product) {
                return ResponseFormator::create(400, "Product ID doesn't match any product");
            }
            $user = User::where('id', $fields['user_id'])->first();
            if (!$user) {
                return ResponseFormator::create(400, "User ID doesn't match any user");
            }
            $fields["amount"] = $product->price * $fields["quantity"];
            $transaction = Transaction::create($fields);
            if (!$transaction) {
                return ResponseFormator::create(501, "Internal Server Error");
            }
            return ResponseFormator::create(201, "Created", $transaction);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function update($id, Request $request)
    {
        $transaction = Transaction::where('id', $id)->first();
        if (!$transaction) {
            return ResponseFormator::create(400, "Product ID doesn't match any product");
        }
        try {
            $fields = $request->validate([
                'product_id' => 'numeric',
                'user_id' => 'numeric',
                'quantity' => 'numeric'
            ]);
            $product = Product::where('id', $fields['product_id'])->first();
            if (!$product) {
                return ResponseFormator::create(400, "Product ID doesn't match any product");
            }
            $user = User::where('id', $fields['user_id'])->first();
            if (!$user) {
                return ResponseFormator::create(400, "User ID doesn't match any user");
            }
            $fields["amount"] = $product->price * $fields["quantity"];
            $transaction = Transaction::create($fields);
            if (!$transaction) {
                return ResponseFormator::create(501, "Internal Server Error");
            }
            return ResponseFormator::create(201, "Created", $transaction);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function delete($id)
    {
        $transaction = Transaction::where('id', $id)->first();
        if (!$transaction) {
            return ResponseFormator::create(400, "Transaction ID doesn't match any transaction");
        }
        $transaction->delete();
        return ResponseFormator::create(200, 'Success');
    }


    // Route::get('/user/transactions', [TransactionController::class, 'findAllUserTransactions']);
    // Route::get('/user/transactions/{id}', [TransactionController::class, 'findUserTransactionById']);
    // Route::post('/user/transactions', [TransactionController::class, 'createUserTransaction']);
    // Route::put('/user/transactions/{id}', [TransactionController::class, 'updateUserTransaction']);
    // Route::delete('/user/transactions/{id}', [TransactionController::class, 'deleteUserTransaction']);

    public function findAllUserTransactions()
    {
        $transactions = Transaction::where('user_id', auth()->user()->id)->get();
        return ResponseFormator::create(200, "Success", $transactions);
    }

    public function findUserTransactionById($id)
    {
        $transaction = Transaction::where('user_id', auth()->user()->id)->where('id', $id)->first();
        if (!$transaction) {
            return ResponseFormator::create(404, "Not Found");
        }
        return ResponseFormator::create(200, "Success", $transaction);
    }

    public function createUserTransaction(Request $request)
    {
        try {
            $fields = $request->validate([
                'product_id' => 'numeric|required',
                'quantity' => 'numeric|required'
            ]);
            $product = Product::where('id', $fields['product_id'])->first();
            if (!$product) {
                return ResponseFormator::create(400, "Product ID doesn't match any product");
            }
            $user = User::where('id', auth()->user()->id)->first();
            if (!$user) {
                return ResponseFormator::create(403, "Forbidden access");
            }
            $fields["amount"] = $product->price * $fields["quantity"];
            $transaction = Transaction::create($fields);
            if (!$transaction) {
                return ResponseFormator::create(501, "Internal Server Error");
            }
            return ResponseFormator::create(201, "Created", $transaction);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function updateUserTransaction($id, Request $request)
    {
        $transaction = Transaction::where('user_id', auth()->user()->id)->where('id', $id)->first();
        if (!$transaction) {
            return ResponseFormator::create(400, "Transaction ID doesn't match any transaction");
        }
        try {
            $fields = $request->validate([
                'product_id' => 'numeric',
                'quantity' => 'numeric'
            ]);
            $product = Product::where('id', $fields['product_id'])->first();
            if (!$product) {
                return ResponseFormator::create(400, "Product ID doesn't match any product");
            }
            $user = User::where('id', auth()->user()->id)->first();
            if (!$user) {
                return ResponseFormator::create(403, "Forbidden access");
            }
            $fields["amount"] = $product->price * $fields["quantity"];
            $transaction->update($fields);
            return ResponseFormator::create(200, "Success", $transaction);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function deleteUserTransaction($id)
    {
        $user = User::where('id', auth()->user()->id)->first();
        if (!$user) {
            return ResponseFormator::create(403, "Forbidden access");
        }
        $transaction = Transaction::where('user_id', $user->id)->where('id', $id)->first();
        if (!$transaction) {
            return ResponseFormator::create(400, "Transaction ID doesn't match any transaction");
        }
        $transaction->delete();
        return ResponseFormator::create(200, 'Success');
    }
}
