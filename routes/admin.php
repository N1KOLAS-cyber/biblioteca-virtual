<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return view('admin.dashboard');
})->name('dashboard');

//gestion de roles
Route::resource('roles', RoleController::class);

//gestion de usuarios
Route::resource('users', UserController::class);

//gestion de autores
Route::resource('authors', AuthorController::class);

//gestion de categorias
Route::resource('categories', CategoryController::class);
