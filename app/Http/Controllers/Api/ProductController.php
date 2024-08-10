<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Utils\ResponseFormator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
                'image' => 'image:jpeg,png,jpg,gif,svg|max:2048|required'
            ]);
            $category = Category::where('id', $fields['category_id'])->first();
            if (!$category) {
                return ResponseFormator::create(400, "Category ID doesn't match any category");
            }

            $uploadFolder = 'products';
            $image = $request->file('image');
            $image_uploaded_path = $image->store($uploadFolder, 'public');
            $fields["image"] = Storage::url($image_uploaded_path);

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
                'image' => 'image:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if (isset($fields['category_id'])) {
                $category = Category::where('id', $fields['category_id'])->first();
                if (!$category) {
                    return ResponseFormator::create(400, "Category ID doesn't match any category");
                }
            }

            if (isset($fields['image'])) {
                $filename = basename($product->image);
                if (Storage::disk('public')->exists("products/" . $filename)) {
                    Storage::disk('public')->delete("products/" . $filename);
                }

                $uploadFolder = 'products';
                $image = $request->file('image');
                $image_uploaded_path = $image->store($uploadFolder, 'public');

                $fields["image"] = Storage::url($image_uploaded_path);
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
        $filename = basename($product->image);
        if (Storage::disk('public')->exists("products/" . $filename)) {
            Storage::disk('public')->delete("products/" . $filename);
        }
        return ResponseFormator::create(200, 'Success');
    }
}
