<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::leftJoin('roles', 'users.roles', '=', 'roles.id')
                ->select('users.*', 'roles.name as role_name')
                ->orderBy('users.roles', 'asc')
                ->get();
        return $users;
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'    
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        $user = new User;
        $user->name = ucwords($request->name);
        $user->email = $request->email;
        $user->password = bcrypt($request->email);
        $user->roles = $request->role;
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'User added']);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'   
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        $user = User::find($request->user_id);
        if($user->name !== $request->name){
            $user->name = ucwords($request->name);
        }
        if($user->email !== $request->email){
            $user->email = $request->email;
        }
        if(!\Hash::check($request->password, $user->password)){
            $user->password = bcrypt($request->email);
        }
        if($user->roles!==$request->role){
            $user->roles = $request->role;
        }
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'User added']);
    }

    public function destroy($user_id){
        $user = User::find($user_id);
        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'User deleted']);
    }
}
