<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function lists()
    {
        $menus = Menu::where('id', '!=', 3)->orderBy('updated_at', 'desc')->get();

        if ($menus->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'Menu data was not found.',
                'data' => [],
            ]);
        }
        foreach ($menus as $menu) {
            $data[] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'is_fish' => $menu->is_fish ? true : false,
            ];
        }
        return response()->json([
            'status' => 200,
            'message' => 'Menu data was fetched.',
            'data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'is_fish' => 'nullable',
            ]);

            if ($validatedData['is_fish'] === true) {
                $validatedData['is_fish'] = 1;
            } else {
                $validatedData['is_fish'] = 0;
            }

            $menu = Menu::create($validatedData);

            return response()->json([
                'status' => 201,
                'message' => 'Menu created successfully.',
                'data' => $menu,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while creating the menu.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function view($id)
    {
        try {
            $menu = Menu::find($id);

            $menu = [
                'id' => $menu->id,
                'name' => $menu->name,
                'is_fish' => $menu->is_fish ? true : false,
            ];

            if (!$menu) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Menu not found.',
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Menu data fetched successfully.',
                'data' => $menu,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching the Menu.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Menu not found.',
                ], 404);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'is_fish' => 'nullable',
            ]);

            if ($validatedData['is_fish'] === true) {
                $validatedData['is_fish'] = 1;
            } else {
                $validatedData['is_fish'] = 0;
            }

            $menu->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Menu updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the menu.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Menu not found.',
                ]);
            }
            $menu->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Menu deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the Menu.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
