<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes([
  'register' => false,
  'reset' => false,
  'verify' => false,
]);

// Auth::routes();

Route::get('/home', 'GeneralController@index')->name('home');
// Route::get('logout', 'HomeController@logout');

Route::get('/', 'GeneralController@index')->name('index');
Route::get('rolesView', 'GeneralController@rolesView')->name('roles');
Route::get('usersView', 'GeneralController@usersView')->name('users');
Route::get('categoriesView', 'GeneralController@categoriesView')->name('categories');
Route::get('expensesView','GeneralController@expensesView')->name('expenses');




// api
Route::group(['prefix' => 'api','middleware'=>'auth'], function () {

  //roles
    Route::group(['prefix' => 'roles'], function () {
        Route::get('index', 'RolesController@index');
        Route::post('store', 'RolesController@store');
        Route::patch('update', 'RolesController@update');
        Route::delete('destroy/{id}', 'RolesController@destroy');
    });

    //users
    Route::group(['prefix' => 'users'], function () {
        Route::get('index', 'UserController@index');
        Route::post('store', 'UserController@store');
        Route::patch('update', 'UserController@update');
        Route::delete('destroy/{id}', 'UserController@destroy');
    });

    //category
    Route::group(['prefix' => 'categories'], function () {
        Route::get('index', 'CategoryController@index');
        Route::post('store', 'CategoryController@store');
        Route::patch('update', 'CategoryController@update');
        Route::delete('destroy/{id}', 'CategoryController@destroy');
    });

    //Expenses
    Route::group(['prefix' => 'expenses'], function () {
        Route::get('index', 'ExpensesController@index');
        Route::post('store', 'ExpensesController@store');
        Route::patch('update', 'ExpensesController@update');
        Route::delete('destroy/{id}', 'ExpensesController@destroy');
    });
  
});









Route::get('role', 'GeneralController@roles');
Route::get('categories', 'GeneralController@categories');
Route::get('dashboard', 'GeneralController@dashboard');