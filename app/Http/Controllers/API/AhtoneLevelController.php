<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AhtoneLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AhtoneLevelController extends Controller
{
    public function lists()
    {
        $ahtoneLevels = AhtoneLevel::orderBy('position', 'asc')->get();

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
                'position' => $ahtoneLevel->position,
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
                'name' => [
                    'required',
                    'string',
                    Rule::unique('ahtone_levels')->whereNull('deleted_at'),
                ],
                'description' => 'nullable',
                'position' => [
                    'required',
                    Rule::unique('ahtone_levels')->whereNull('deleted_at'),
                ],
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
                'description' => $ahtoneLevel->description,
                'position' => $ahtoneLevel->position,
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
                // 'name' => 'required|string|unique:ahtone_levels,name' . ",$id",
                'name' => [
                    'required',
                    'string',
                    Rule::unique('ahtone_levels')->ignore($id)->whereNull('deleted_at'),
                ],
                'description' => 'nullable',
                'position' => [
                    'required',
                    Rule::unique('ahtone_levels')->ignore($id)->whereNull('deleted_at'),
                ],
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