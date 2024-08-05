<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function lists()
    {
        $categories = Category::select('id', 'name', 'description', 'status')->get();
        if ($categories->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'Category data was not found.',
                'data' => [],
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Category data was fetched.',
            'data' => $categories]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable',
            ]);

            $category = Category::create($validatedData);

            return response()->json([
                'status' => 201,
                'message' => 'Category created successfully.',
                'data' => $category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while creating the category.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function view($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found.',
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Category data fetched successfully.',
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching the category.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found.',
                ], 404);
            }

            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable',
            ]);

            $category->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Category updated successfully.',
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category not found.',
                ]);
            }
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Category deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the category.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}