<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Utils\ResponseFormator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{

    public function findAll()
    {
        $products = Product::all();
        return ResponseFormator::create(200, "Success", $products);
    }

    public function findById($id)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return ResponseFormator::create(404, "Not Found");
        }
        return ResponseFormator::create(200, "Success", $product);
    }

    public function create(Request $request)
    {
        try {
            $fields = $request->validate([
                'name' => 'string|required',
                'category_id' => 'numeric|required',
                'price' => 'numeric|required',
                'image' => 'string|required'
            ]);
            $category = Category::where('id', $fields['category_id'])->first();
            if (!$category) {
                return ResponseFormator::create(400, "Category ID doesn't match any category");
            }
            $product = Product::create($fields);
            if (!$product) {
                return ResponseFormator::create(501, "Internal Server Error");
            }
            return ResponseFormator::create(201, "Created", $product);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function update($id, Request $request)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return ResponseFormator::create(400, "Product ID doesn't match any product");
        }
        try {
            $fields = $request->validate([
                'name' => 'string',
                'category_id' => 'numeric',
                'price' => 'numeric',
                'image' => 'string'
            ]);
            $category = Category::where('id', $fields['category_id'])->first();
            if (!$category) {
                return ResponseFormator::create(400, "Category ID doesn't match any category");
            }
            $product->update($fields);
            if (!$product) {
                return ResponseFormator::create(501, "Internal Server Error");
            }
            return ResponseFormator::create(201, "Created", $product);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function delete($id)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return ResponseFormator::create(400, "Product ID doesn't match any product");
        }
        $product->delete();
        return ResponseFormator::create(200, 'Success');
    }
}
