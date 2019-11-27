<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Models\User;
use App\Models\Categories;
use App\Models\Expenses;
use Auth;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('dashboard');
    }

    public function rolesView(){
        return view('roles');
    }

    public function usersView(){
        return view('users');
    }


    public function categoriesView(){
        return view('categories');
    }

    public function expensesView(){
        return view('expenses');
    }



    public function roles() 
    {
        $roles = Roles::get();
        return $roles;
    }

    public function categories()
    {
        $categories = Categories::get();
        return $categories;
    }

    public function dashboard()
    {
        $expense = Expenses::where('user', Auth::user()->id)
        ->leftJoin('categories', 'expenses.category', 'categories.id')
        ->select('expenses.*', 'categories.name as category_name')
        ->get();
        return $expense;
    }
}
