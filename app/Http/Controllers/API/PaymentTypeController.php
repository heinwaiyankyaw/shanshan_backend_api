<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function lists()
    {
        $paymentTypes = PaymentType::orderBy('updated_at', 'desc')->get();

        if ($paymentTypes->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'Payment Type data was not found.',
                'data' => [],
            ]);
        }
        foreach ($paymentTypes as $paymentType) {
            $data[] = [
                'id' => $paymentType->id,
                'name' => $paymentType->name,
                'type' => $paymentType->type,
            ];
        }
        return response()->json([
            'status' => 200,
            'message' => 'Payment Type data was fetched.',
            'data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|unique:payment_types,name',
                'type' => 'required',
            ]);

            $paymentType = PaymentType::create($validatedData);

            return response()->json([
                'status' => 201,
                'message' => 'SpicyLevel created successfully.',
                'data' => $paymentType,
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
            $paymentType = PaymentType::find($id);

            $paymentType = [
                'id' => $paymentType->id,
                'name' => $paymentType->name,
            ];

            if (!$paymentType) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Payment Type not found.',
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Payment Type data fetched successfully.',
                'data' => $paymentType,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching the Payment Type.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $paymentType = PaymentType::find($id);

            if (!$paymentType) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Payment Type not found.',
                ], 404);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|unique:payment_types,name' . ",$id",
                'type' => 'required',
            ]);

            $paymentType->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Payment Type updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the Payment Type.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $paymentType = PaymentType::find($id);

            if (!$paymentType) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Payment Type not found.',
                ]);
            }
            $paymentType->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Payment Type deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the Payment Type.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}