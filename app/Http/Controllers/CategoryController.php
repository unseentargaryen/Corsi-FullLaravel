<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', ['categories' => Category::all()]);
    }

    public function all()
    {
        $categories = Category::where('visible',false);

        return response()->json($categories);
    }

    public function show($id)
    {
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

    public function create(Request $request)
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

    public function edit(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $id = $request->id;
        $category = Category::find($id);

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

    public function toggleVisibility(Request $request)
    {
        $id = $request->get('id');
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->visible = !$category->visible;

        $category->save();

        return response()->json([
            'success' => true
        ], 200);
    }


}
