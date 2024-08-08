<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Remark;
use Illuminate\Http\Request;

class RemarkController extends Controller
{
    public function lists()
    {
        $remarks = Remark::orderBy('updated_at', 'desc')->get();

        if ($remarks->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'Remark data was not found.',
                'data' => [],
            ]);
        }
        foreach ($remarks as $remark) {
            $data[] = [
                'id' => $remark->id,
                'name' => $remark->name,
                'description' => $remark->description,
            ];
        }
        return response()->json([
            'status' => 200,
            'message' => 'Remark data was fetched.',
            'data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|unique:spicy_levels,name',
                'description' => 'nullable',
            ]);

            $remark = Remark::create($validatedData);

            return response()->json([
                'status' => 201,
                'message' => 'SpicyLevel created successfully.',
                'data' => $remark,
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
            $remark = Remark::find($id);

            $remark = [
                'id' => $remark->id,
                'name' => $remark->name,
            ];

            if (!$remark) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Remark not found.',
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Remark data fetched successfully.',
                'data' => $remark,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching the Remark.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $remark = Remark::find($id);

            if (!$remark) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Remark not found.',
                ], 404);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|unique:spicy_levels,name' . ",$id",
            ]);

            $remark->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Remark updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the Remark.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $remark = Remark::find($id);

            if (!$remark) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Remark not found.',
                ]);
            }
            $remark->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Remark deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the Remark.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}