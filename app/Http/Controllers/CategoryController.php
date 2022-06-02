<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function show($id){
        $category = Category::all()->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found '
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category->toArray()
        ], 400);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $category = new Category();
        $category->name = $request->name;

        if ($category->save($category->toArray()))
            return response()->json([
                'success' => true,
                'data' => $category->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Category not added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $category = Category::all()->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $updated = $category->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Category can not be updated'
            ], 500);
    }

    public function destroy($id)
    {
        $category = Category::all()->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        if ($category->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Category can not be deleted'
            ], 500);
        }
    }
}
