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
            if (isset($fields['product_id'])) {
                $product = Product::where('id', $fields['product_id'])->first();
                if (!$product) {
                    return ResponseFormator::create(400, "Product ID doesn't match any product");
                }
                $fields["amount"] = $product->price * $fields["quantity"];
            }
            if (isset($fields['user_id'])) {
                $user = User::where('id', $fields['user_id'])->first();
                if (!$user) {
                    return ResponseFormator::create(400, "User ID doesn't match any user");
                }
            }
            if (isset($fields['quantity'])) {
                $fields["amount"] = $product->price * $fields["quantity"];
            }
            $transaction->update($fields);
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
    public function findAllUserTransactions()
    {
        $transactions = auth()->user()->transactions;
        return ResponseFormator::create(200, "Success", $transactions);
    }

    public function findUserTransactionById($id)
    {
        $transaction = auth()->user()->transactions->where('id', $id)->first();
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
            $fields["user_id"] = auth()->user()->id;
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
        $transaction = auth()->user()->transactions->where('id', $id)->first();
        if (!$transaction) {
            return ResponseFormator::create(400, "Transaction ID doesn't match any transaction");
        }
        try {
            $fields = $request->validate([
                'product_id' => 'numeric',
                'quantity' => 'numeric'
            ]);
            if (isset($fields['product_id'])) {
                $product = Product::where('id', $fields['product_id'])->first();
                if (!$product) {
                    return ResponseFormator::create(400, "Product ID doesn't match any product");
                }
            }
            if (isset($fields['quantity'])) {
                $fields["amount"] = $product->price * $fields["quantity"];
            }
            $transaction->update($fields);
            return ResponseFormator::create(200, "Success", $transaction);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function deleteUserTransaction($id)
    {
        $transaction = auth()->user()->transactions->where('id', $id)->first();
        if (!$transaction) {
            return ResponseFormator::create(400, "Transaction ID doesn't match any transaction");
        }
        $transaction->delete();
        return ResponseFormator::create(200, 'Success');
    }
}
