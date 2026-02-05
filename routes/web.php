<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ParentMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use App\Http\Middleware\AccountantMiddleware;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;


Route::get('/',[AuthController::class,'login']);
Route::post('/login',[AuthController::class,'AuthLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('forget-password', [AuthController::class, 'forgetPassword'])->name('forget-password');
Route::post('forget-password', [AuthController::class, 'PostForgetPassword']);
Route::get('reset/{token}', [AuthController::class, 'reset']);
Route::post('reset/{token}', [AuthController::class, 'postReset']);




Route::group(['middleware'=>'admin'],function(){
        //AdminController 
        Route::get('/admin/dashboard',[DashboardController::class,'dashboard']);
        Route::get('admin/admin/list',[AdminController::class,'list']);
        Route::get('admin/admin/add',[AdminController::class,'add']);
        Route::post('admin/admin/add',[AdminController::class,'insert']);
        Route::get('admin/admin/edit/{id}',[AdminController::class,'edit']);
        Route::post('admin/admin/edit/{id}',[AdminController::class,'update']);
        Route::get('admin/admin/delete/{id}',[AdminController::class,'delete']);
        //ClassController
        Route::get('admin/class/list',[ClassController::class,'list']);
        Route::get('admin/class/add',[ClassController::class,'add']);
        Route::post('admin/class/add',[ClassController::class,'insert']);
        Route::get('admin/class/edit/{id}',[ClassController::class,'edit']);
        Route::post('admin/class/edit/{id}',[ClassController::class,'update']);
        Route::get('admin/class/delete/{id}',[ClassController::class,'delete']);



});

Route::group(['middleware'=>'teacher'],function(){
        Route::get('/teacher/dashboard',[DashboardController::class,'dashboard']);

});

Route::group(['middleware'=>'student'],function(){
        Route::get('/student/dashboard',[DashboardController::class,'dashboard']);


});

Route::group(['middleware'=>'parent'],function(){
        Route::get('/parent/dashboard',[DashboardController::class,'dashboard']);

});

Route::group(['middleware'=>'accountant'],function(){
        Route::get('/accountant/dashboard',[DashboardController::class,'dashboard']);

});

