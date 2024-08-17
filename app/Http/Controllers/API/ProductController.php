<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function lists()
    {
        $products = Product::select('id', 'name', 'qty', 'is_gram', 'prices', 'category_id')->with('category')->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'Product data was not found.',
                'data' => [],
            ]);
        }
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $product->qty,
                'is_gram' => $product->is_gram ? true : false,
                'prices' => $product->prices * 1,
                'category' => $product->category->name,
            ];
        }
        return response()->json([
            'status' => 200,
            'message' => 'Product data was fetched.',
            'data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'category_id' => 'required',
                'name' => [
                    'required',
                    'string',
                    Rule::unique('products')->whereNull('deleted_at'),
                ],
                'qty' => 'required',
                'is_gram' => 'required|boolean',
                'prices' => 'required',
            ]);

            if ($validatedData['is_gram'] === true) {
                $validatedData['is_gram'] = 1;
            } else {
                $validatedData['is_gram'] = 0;
            }

            $product = Product::create($validatedData);

            return response()->json([
                'status' => 201,
                'message' => 'Product created successfully.',
                'data' => $product,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while creating the product.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function view($id)
    {
        try {
            $product = Product::find($id);

            $product = [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $product->qty,
                'is_gram' => $product->is_gram ? true : false,
                'prices' => $product->prices * 1,
                'category' => $product->category->name,
            ];

            if (!$product) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found.',
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Product data fetched successfully.',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching the product.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found.',
                ], 404);
            }

            $validatedData = $request->validate([
                'category_id' => 'required',
                'name' => [
                    'required',
                    'string',
                    Rule::unique('products')->ignore($id)->whereNull('deleted_at'),
                ],
                'qty' => 'required',
                'is_gram' => 'required|boolean',
                'prices' => 'required',
            ]);

            if ($validatedData['is_gram'] === true) {
                $validatedData['is_gram'] = 1;
            } else {
                $validatedData['is_gram'] = 0;
            }

            $product->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Product updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found.',
                ]);
            }
            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Product deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the product.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
