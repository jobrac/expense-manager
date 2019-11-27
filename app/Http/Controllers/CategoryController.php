<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Validator;

class CategoryController extends Controller
{
    //

    public function index(){
        $categories = Categories::get();
        return $categories;
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        $category = new Categories;
        $category->name = ucwords($request->name);
        $category->description = ucwords($request->description);
        $category->save();
        return response()->json(['status' => 'success', 'message' => 'Category added']);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        $category = Categories::find($request->category_id);
        $category->name = ucwords($request->name);
        $category->description = ucwords($request->description);
        $category->save();
        return response()->json(['status' => 'success', 'message' => 'Category updated']);
    }

    public function destroy($category_id){
        $category = Categories::find($category_id);
        $category->delete();
        return response()->json(['status' => 'success', 'message' => 'Category deleted']);
    }
}
