<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

//gestion de roles
Route::resource('roles', RoleController::class);

//gestion de usuarios
Route::resource('users', UserController::class);

//gestion de autores
Route::resource('authors', AuthorController::class);

//gestion de categorias
Route::resource('categories', CategoryController::class);

//gestion de libros
Route::resource('books', BookController::class);

//gestion de planes
Route::resource('plans', PlanController::class);
