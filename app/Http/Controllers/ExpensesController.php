<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expenses;
use Validator;
use Auth;

class ExpensesController extends Controller
{
    public function index(){
        $expenses = Expenses::where('user', Auth::user()->id)
                ->leftJoin('categories', 'categories.id', 'expenses.category')
                ->select('expenses.*', 'categories.name as category_name')
                ->orderBy('expenses.entry', 'desc')
                ->get();
        return $expenses;
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        $expense = new Expenses;
        $expense->category = $request->category;
        $expense->amount = $request->amount;
        $expense->entry = $request->date;
        $expense->user = Auth::user()->id;
        $expense->save();
        return response()->json(['status' => 'success', 'message' => 'User added']);
    }


    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'expense_id' => 'required',
            'category' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        $expense = Expenses::find($request->expense_id);
        $expense->category = $request->category;
        $expense->amount = $request->amount;
        $expense->entry = $request->date;
        $expense->save();
        return response()->json(['status' => 'success', 'message' => 'Expense added']);
    }


    public function destroy($expense_id){
        $expense = Expenses::find($expense_id);
        $expense->delete();
        return response()->json(['status' => 'success', 'message' => 'Expense deleted']);
    }
}
