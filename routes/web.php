<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\UsersController;


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


Route::prefix('api')->group(function (){
    
    // Users routes
    Route::post('user/create', [UsersController::class, 'create']);
    Route::put('user/update/{id}',  [UsersController::class, 'update']);
    Route::get('user/validate',  [UsersController::class, 'emailValidate'])->name('email.validate');

    // Contacts routes
    Route::post('contact/create', [ContactsController::class, 'create']);

});