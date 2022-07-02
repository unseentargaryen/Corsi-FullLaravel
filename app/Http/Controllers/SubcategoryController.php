<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::all();
        $categories = Category::all();

        return view('admin.subcategories.index', ['subcategories' => $subcategories, 'categories' => $categories]);
    }

    public function all()
    {
        $subcategories = DB::table('subcategories')
            ->join('categories', 'categories.id', '=', 'subcategories.category_id')
            ->select('subcategories.*', 'categories.name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $subcategories
        ]);
    }

    public function show($id)
    {
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

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
        ]);

        $subcategory = new Subcategory();
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;

        if ($subcategory->save($subcategory->toArray()))
            return response()->json([
                'success' => true,
                'data' => $subcategory->toArray()
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
            'name' => 'required',
            'category_id' => 'required',
        ]);
        $id = $request->id;
        $subcategory = Subcategory::find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'Subcategory not found'
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
                'message' => 'Subcategory can not be updated'
            ], 500);
    }

    public function toggleVisibility(Request $request)
    {
        $id = $request->get('id');
        $subcategory = Subcategory::find($id);

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => 'Subcategory not found'
            ], 404);
        }

        $subcategory->visible = !$subcategory->visible;

        $subcategory->save();

        return response()->json([
            'success' => true
        ], 200);
    }
}
