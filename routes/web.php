<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ParentMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use App\Http\Middleware\AccountantMiddleware;
use App\Http\Controllers\DashboardController;



Route::get('/',[AuthController::class,'login']);
Route::post('/login',[AuthController::class,'AuthLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');




Route::group(['middleware'=>'admin'],function(){
    Route::get('/admin/dashboard',[DashboardController::class,'dashboard']);
    Route::get('admin/admin/list', function () {return view('admin.admin.list');})->name('admin.admin.list');

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

