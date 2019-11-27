<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roles;
use Validator;

class RolesController extends Controller
{
    public function index(){
        $roles = Roles::paginate(10);
        return $roles;
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        $role = new Roles;
        $role->name = ucwords($request->name);
        $role->description = ucwords($request->description);
        $role->save();
        return response()->json(['status' => 'success', 'message' => 'Role added']);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        $role = Roles::find($request->role_id);
        $role->name = ucwords($request->name);
        $role->description = ucwords($request->description);
        $role->save();
        return response()->json(['status' => 'success', 'message' => 'Role updated']);
    }

    public function destroy($role_id){
        $role = Roles::find($role_id);
        $role->delete();
        return response()->json(['status' => 'success', 'message' => 'Role deleted']);
    }
}