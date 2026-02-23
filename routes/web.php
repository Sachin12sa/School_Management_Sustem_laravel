<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssignClassTeacherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\ClassTimeTableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
         //student 
        Route::get('admin/student/list',[StudentController::class,'list']);
        Route::get('admin/student/add',[StudentController::class,'add']);
        Route::post('admin/student/add',[StudentController::class,'insert']);
        Route::get('admin/student/edit/{id}',[StudentController::class,'edit']);
        Route::post('admin/student/edit/{id}',[StudentController::class,'update']);
        Route::get('admin/student/delete/{id}',[StudentController::class,'delete']); 
        //parent
        Route::get('admin/parent/list',[ParentController::class,'list']);
        Route::get('admin/parent/add',[ParentController::class,'add']);
        Route::post('admin/parent/add',[ParentController::class,'insert']);
        Route::get('admin/parent/edit/{id}',[ParentController::class,'edit']);
        Route::post('admin/parent/edit/{id}',[ParentController::class,'update']);
        Route::get('admin/parent/delete/{id}',[ParentController::class,'delete']); 
        Route::get('admin/parent/my-student/{id}',[ParentController::class,'myStudent']);
        Route::get('admin/parent/assign_student_parent/{parent_id}/{student_id}',[ParentController::class,'assignStudentParent']);
        Route::get('admin/parent/assign_student_parent_delete/{student_id}',[ParentController::class,'assignStudentParentDelete']);
        //teacher
        Route::get('admin/teacher/list',[TeacherController::class,'list']);
        Route::get('admin/teacher/add',[TeacherController::class,'add']);
        Route::post('admin/teacher/add',[TeacherController::class,'insert']);
        Route::get('admin/teacher/edit/{id}',[TeacherController::class,'edit']);
        Route::post('admin/teacher/edit/{id}',[TeacherController::class,'update']);
        Route::get('admin/teacher/delete/{id}',[TeacherController::class,'delete']); 

        //ClassController
        Route::get('admin/class/list',[ClassController::class,'list']);
        Route::get('admin/class/add',[ClassController::class,'add']);
        Route::post('admin/class/add',[ClassController::class,'insert']);
        Route::get('admin/class/edit/{id}',[ClassController::class,'edit']);
        Route::post('admin/class/edit/{id}',[ClassController::class,'update']);
        Route::get('admin/class/delete/{id}',[ClassController::class,'delete']);
        //SubjectController
        Route::get('admin/subject/list',[SubjectController::class,'list']);
        Route::get('admin/subject/add',[SubjectController::class,'add']);
        Route::post('admin/subject/add',[SubjectController::class,'insert']);
        Route::get('admin/subject/edit/{id}',[SubjectController::class,'edit']);
        Route::post('admin/subject/edit/{id}',[SubjectController::class,'update']);
        Route::get('admin/subject/delete/{id}',[SubjectController::class,'delete']);
        //assign_subject
        Route::get('admin/assign_subject/list',[ClassSubjectController::class,'list']);
        Route::get('admin/assign_subject/add',[ClassSubjectController::class,'add']);
        Route::post('admin/assign_subject/add',[ClassSubjectController::class,'insert']);
        Route::get('admin/assign_subject/edit/{id}',[ClassSubjectController::class,'edit']);
        Route::post('admin/assign_subject/edit/{id}',[ClassSubjectController::class,'update']);
        Route::get('admin/assign_subject/delete/{id}',[ClassSubjectController::class,'delete']);
        Route::get('admin/assign_subject/edit_single/{id}',[ClassSubjectController::class,'edit_single']);
        Route::post('admin/assign_subject/edit_single/{id}',[ClassSubjectController::class,'update_single']);
        // assign_class_teacher
        Route::get('admin/assign_class_teacher/list',[AssignClassTeacherController::class,'list']);
        Route::get('admin/assign_class_teacher/add',[AssignClassTeacherController::class,'add']);
        Route::post('admin/assign_class_teacher/add',[AssignClassTeacherController::class,'insert']);
        Route::get('admin/assign_class_teacher/edit/{id}',[AssignClassTeacherController::class,'edit']);
        Route::post('admin/assign_class_teacher/edit/{id}',[AssignClassTeacherController::class,'update']);
        Route::get('admin/assign_class_teacher/delete/{id}',[AssignClassTeacherController::class,'delete']);
        Route::get('admin/assign_class_teacher/edit_single/{id}',[AssignClassTeacherController::class,'edit_single']);
        Route::post('admin/assign_class_teacher/edit_single/{id}',[AssignClassTeacherController::class,'update_single']);
        // class_timetable
        Route::get('admin/class_timetable/list',[ClassTimeTableController::class,'list']);
        Route::post('admin/class_timetable/get_subject', [ClassTimeTableController::class, 'get_subject']);      
        Route::post('admin/class_timetable/add', [ClassTimeTableController::class, 'insert_update']);    
        
        // Examinatoin
        // exam
        Route::get('admin/examination/exam/list',[ExaminationController::class,'exam_list']);
        Route::get('admin/examination/exam/add',[ExaminationController::class,'exam_add']);
        Route::post('admin/examination/exam/add',[ExaminationController::class,'exam_insert']);
        Route::get('admin/examination/exam/edit/{id}',[ExaminationController::class,'exam_edit']);
        Route::post('admin/examination/exam/edit/{id}',[ExaminationController::class,'exam_update']);
        Route::get('admin/examination/exam/delete/{id}',[ExaminationController::class,'exam_delete']);
        // exam schedule admin/examination/exam_schedule
        Route::get('admin/examination/exam_schedule',[ExaminationController::class,'exam_schedule']);
        Route::post('admin/examination/exam_schedule_insert',[ExaminationController::class,'exam_schedule_insert']);
        Route::get('admin/examination/exam_schedule/delete/{id}',[ExaminationController::class,'examDateDelete']);
                
        // My Account
        Route::get('admin/account',[UserController::class,'MyAccount']);
        Route::post('admin/account',[UserController::class,'update']);
        //change_password
        Route::get('admin/profile/change_password',[UserController::class,'change_password']);
        Route::post('admin/profile/change_password',[UserController::class,'update_change_password']);  
       

});
// Teacher Section
Route::group(['middleware'=>'teacher'],function(){
        Route::get('/teacher/dashboard',[DashboardController::class,'dashboard']);
        // my_class_subject
        Route::get('teacher/my_class_subject',[AssignClassTeacherController::class,'MyClassSubject']);
        // my_student
        Route::get('teacher/my_student',[StudentController::class,'MyStudent']);
         // my_timetable
        Route::get('teacher/my_class_subject/class_timetable/{class_id}/{subject_id}',[ClassTimeTableController::class,'myTimetableTeacher']);
        // my_exam_timetable
        Route::get('teacher/my_exam_timetable',[ExaminationController::class,'myExamTimetableTeacher']);
                // My Calender
        Route::get('teacher/my_calender',[CalenderController::class,'MyTeacherCalendar']);
        //change_password
        Route::get('teacher/profile/change_password',[UserController::class,'change_password']);
        Route::post('teacher/profile/change_password',[UserController::class,'update_change_password']);   
        // My Account
        Route::get('teacher/account',[UserController::class,'MyAccount']);
        Route::post('teacher/account',[UserController::class,'update']);

});

//  Student
Route::group(['middleware'=>'student'],function(){
        Route::get('/student/dashboard',[DashboardController::class,'dashboard']);
        // my_subject
        Route::get('student/my_subject',[SubjectController::class,'mySubject']);
        // my_timetable
        Route::get('student/my_timetable',[ClassTimeTableController::class,'myTimetable']);
        // my_exam_timetable
        Route::get('student/my_exam_timetable',[ExaminationController::class,'myExamTimetable']);
        //change_password
        Route::get('student/profile/change_password',[UserController::class,'change_password']);
        Route::post('student/profile/change_password',[UserController::class,'update_change_password']);  
        // My Calender
        Route::get('student/my_calender',[CalenderController::class,'MyCalender']);
        // Route::post('student/my_calender',[CalenderController::class,'update']);
        // My Account
        Route::get('student/account',[UserController::class,'MyAccount']);
        Route::post('student/account',[UserController::class,'update']);

});
//  parent
Route::group(['middleware'=>'parent'],function(){
        Route::get('/parent/dashboard',[DashboardController::class,'dashboard']);
        // my_student
        Route::get('parent/my_student',[ParentController::class,'myStudentParent']);
        // view student subject
        Route::get('parent/my_student/subject/{student_id}',[SubjectController::class,'ParentStudentSubject']);
        // time table parent/my_class_subject/class_timetable/
        Route::get('parent/my_student/subject/class_timetable/{class_id}/{subject_id}/{student_id}',[ClassTimeTableController::class,'myTimetableParent']);
        // student exam timetable 
        Route::get('parent/my_student/exam_timetable/{student_id}',[ExaminationController::class,'ParentMyExamTimetable']);
        // stuedent calendar
        Route::get('parent/my_student/calendar/{student_id}',[CalenderController::class,'MyParentCalendar']);
        //change_password
        Route::get('parent/profile/change_password',[UserController::class,'change_password']);
        Route::post('parent/profile/change_password',[UserController::class,'update_change_password']);
        // My Account
        Route::get('parent/account',[UserController::class,'MyAccount']);
        Route::post('parent/account',[UserController::class,'update']);   


});

Route::group(['middleware'=>'accountant'],function(){
        Route::get('/accountant/dashboard',[DashboardController::class,'dashboard']);
        //change_password
        Route::get('accountant/profile/change_password',[UserController::class,'change_password']);
        Route::post('accountant/profile/change_password',[UserController::class,'update_change_password']);   

});

