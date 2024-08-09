<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Utils\ResponseFormator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function findAll()
    {
        $categories = Category::all();
        return ResponseFormator::create(200, "Success", $categories);
    }

    public function findById($id)
    {
        $category = Category::where('id', $id)->first();
        if (!$category) {
            return ResponseFormator::create(404, "Not Found");
        }
        return ResponseFormator::create(200, "Success", $category);
    }

    public function create(Request $request)
    {
        try {
            $fields = $request->validate([
                'name' => 'string|required'
            ]);
            $category = Category::create($fields);
            if (!$category) {
                return ResponseFormator::create(501, "Internal Server Error");
            }
            return ResponseFormator::create(201, "Created", $category);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function update($id, Request $request)
    {
        $category = Category::where('id', $id)->first();
        if (!$category) {
            return ResponseFormator::create(404, "Not Found");
        }
        try {
            $fields = $request->validate([
                'name' => 'string'
            ]);
            $category->update($fields);
            if (!$category) {
                return ResponseFormator::create(501, "Internal Server Error");
            }
            return ResponseFormator::create(201, "Created", $category);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function delete($id)
    {
        $category = Category::where('id', $id)->first();
        if (!$category) {
            return ResponseFormator::create(400, "Category ID doesn't match any category");
        }
        $category->delete();
        return ResponseFormator::create(200, 'Success');
    }
}
