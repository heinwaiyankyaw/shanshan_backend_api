<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SpicyLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SpicyLevelController extends Controller
{
    public function lists()
    {
        $spicyLevels = SpicyLevel::orderBy('updated_at', 'desc')->get();

        if ($spicyLevels->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'Spicy data was not found.',
                'data' => [],
            ]);
        }
        foreach ($spicyLevels as $spicyLevel) {
            $data[] = [
                'id' => $spicyLevel->id,
                'name' => $spicyLevel->name,
                'description' => $spicyLevel->description,
            ];
        }
        return response()->json([
            'status' => 200,
            'message' => 'Spicy Level data was fetched.',
            'data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('spicy_levels')->whereNull('deleted_at'),
                ],
                'description' => 'nullable',
            ]);

            $spicyLevel = SpicyLevel::create($validatedData);

            return response()->json([
                'status' => 201,
                'message' => 'SpicyLevel created successfully.',
                'data' => $spicyLevel,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while creating the SpicyLevel.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function view($id)
    {
        try {
            $spicyLevel = SpicyLevel::find($id);

            $spicyLevel = [
                'id' => $spicyLevel->id,
                'name' => $spicyLevel->name,
            ];

            if (!$spicyLevel) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Spicy Level not found.',
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Spicy Level data fetched successfully.',
                'data' => $spicyLevel,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching the Spicy Level.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $spicyLevel = SpicyLevel::find($id);

            if (!$spicyLevel) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Spicy Level not found.',
                ], 404);
            }

            $validatedData = $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('spicy_levels')->ignore($id)->whereNull('deleted_at'),
                ],
                'description' => 'nullable',
            ]);

            $spicyLevel->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Spicy Level updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the Spicy Level.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $spicyLevel = SpicyLevel::find($id);

            if (!$spicyLevel) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Spicy Level not found.',
                ]);
            }
            $spicyLevel->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Spicy Level deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the Spicy Level.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
