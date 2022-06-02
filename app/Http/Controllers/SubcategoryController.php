<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategory = Subcategory::all();

        return response()->json([
            'success' => true,
            'data' => $subcategory
        ]);
    }

    public function show($id){
        $subcategory = Subcategory::all()->find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'subcategory not found '
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $subcategory->toArray()
        ], 404);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $subcategory = new subcategory();
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->name;

        if ($subcategory->save($subcategory->toArray()))
            return response()->json([
                'success' => true,
                'data' => $subcategory->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'subcategory not added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $subcategory = Subcategory::all()->find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'subcategory not found'
            ], 404);
        }

        $updated = $subcategory->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'subcategory can not be updated'
            ], 500);
    }

    public function destroy($id)
    {
        $subcategory = Subcategory::all()->find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'subcategory not found'
            ], 404);
        }

        if ($subcategory->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'subcategory can not be deleted'
            ], 500);
        }
    }}
