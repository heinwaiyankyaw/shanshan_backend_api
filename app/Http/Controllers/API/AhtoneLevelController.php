<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AhtoneLevel;
use Illuminate\Http\Request;

class AhtoneLevelController extends Controller
{
    public function lists()
    {
        $ahtoneLevels = AhtoneLevel::orderBy('updated_at', 'desc')->get();

        if ($ahtoneLevels->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'Ahtone data was not found.',
                'data' => [],
            ]);
        }
        foreach ($ahtoneLevels as $ahtoneLevel) {
            $data[] = [
                'id' => $ahtoneLevel->id,
                'name' => $ahtoneLevel->name,
                'description' => $ahtoneLevel->description,
            ];
        }
        return response()->json([
            'status' => 200,
            'message' => 'Ahtone Level data was fetched.',
            'data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable',
            ]);

            $ahtoneLevel = AhtoneLevel::create($validatedData);

            return response()->json([
                'status' => 201,
                'message' => 'Ahtone Level created successfully.',
                'data' => $ahtoneLevel,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while creating the Ahtone Level.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function view($id)
    {
        try {
            $ahtoneLevel = AhtoneLevel::find($id);

            $ahtoneLevel = [
                'id' => $ahtoneLevel->id,
                'name' => $ahtoneLevel->name,
            ];

            if (!$ahtoneLevel) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Ahtone Level not found.',
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Ahtone Level data fetched successfully.',
                'data' => $ahtoneLevel,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching the Ahtone Level.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $ahtoneLevel = AhtoneLevel::find($id);

            if (!$ahtoneLevel) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Ahtone Level not found.',
                ], 404);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $ahtoneLevel->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Ahtone Level updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the Ahtone Level.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $ahtoneLevel = AhtoneLevel::find($id);

            if (!$ahtoneLevel) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Ahtone Level not found.',
                ]);
            }
            $ahtoneLevel->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Ahtone Level deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the Ahtone Level.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
