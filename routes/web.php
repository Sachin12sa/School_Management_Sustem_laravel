<?php

use App\Http\Controllers\AccountantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssignClassTeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\ClassTimeTableController;
use App\Http\Controllers\CommunicateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\FeeTypeController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\LibrarianController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'AuthLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('forget-password', [AuthController::class, 'forgetPassword'])->name('forget-password');
Route::post('forget-password', [AuthController::class, 'PostForgetPassword']);
Route::get('reset/{token}', [AuthController::class, 'reset']);
Route::post('reset/{token}', [AuthController::class, 'postReset']);

Route::group(['middleware' => 'admin'], function () {
    // AdminController
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard']);
    Route::get('admin/admin/list', [AdminController::class, 'list']);
    Route::get('admin/admin/add', [AdminController::class, 'add']);
    Route::post('admin/admin/add', [AdminController::class, 'insert']);
    Route::get('admin/admin/edit/{id}', [AdminController::class, 'edit']);
    Route::post('admin/admin/edit/{id}', [AdminController::class, 'update']);
    Route::get('admin/admin/delete/{id}', [AdminController::class, 'delete']);
    // Accountant
    Route::get('admin/accountant/list', [AccountantController::class, 'list']);
    Route::get('admin/accountant/add', [AccountantController::class, 'add']);
    Route::post('admin/accountant/add', [AccountantController::class, 'insert']);
    Route::get('admin/accountant/edit/{id}', [AccountantController::class, 'edit']);
    Route::post('admin/accountant/edit/{id}', [AccountantController::class, 'update']);
    Route::get('admin/accountant/delete/{id}', [AccountantController::class, 'delete']);
 // librarain
    Route::get('admin/librarian/list', [LibrarianController::class, 'list']);
    Route::get('admin/librarian/add', [LibrarianController::class, 'add']);
    Route::post('admin/librarian/add', [LibrarianController::class, 'insert']);
    Route::get('admin/librarian/edit/{id}', [LibrarianController::class, 'edit']);
    Route::post('admin/librarian/edit/{id}', [LibrarianController::class, 'update']);
    Route::get('admin/librarian/delete/{id}', [LibrarianController::class, 'delete']); 

    // student
    Route::get('admin/student/list', [StudentController::class, 'list']);
    Route::get('admin/student/add', [StudentController::class, 'add']);
    Route::post('admin/student/add', [StudentController::class, 'insert']);
    Route::get('admin/student/edit/{id}', [StudentController::class, 'edit']);
    Route::post('admin/student/edit/{id}', [StudentController::class, 'update']);
    Route::get('admin/student/delete/{id}', [StudentController::class, 'delete']);
    // parent
    Route::get('admin/parent/list', [ParentController::class, 'list']);
    Route::get('admin/parent/add', [ParentController::class, 'add']);
    Route::post('admin/parent/add', [ParentController::class, 'insert']);
    Route::get('admin/parent/edit/{id}', [ParentController::class, 'edit']);
    Route::post('admin/parent/edit/{id}', [ParentController::class, 'update']);
    Route::get('admin/parent/delete/{id}', [ParentController::class, 'delete']);
    Route::get('admin/parent/my-student/{id}', [ParentController::class, 'myStudent']);
    Route::get('admin/parent/assign_student_parent/{parent_id}/{student_id}', [ParentController::class, 'assignStudentParent']);
    Route::get('admin/parent/assign_student_parent_delete/{student_id}', [ParentController::class, 'assignStudentParentDelete']);
    // teacher
    Route::get('admin/teacher/list', [TeacherController::class, 'list']);
    Route::get('admin/teacher/add', [TeacherController::class, 'add']);
    Route::post('admin/teacher/add', [TeacherController::class, 'insert']);
    Route::get('admin/teacher/edit/{id}', [TeacherController::class, 'edit']);
    Route::post('admin/teacher/edit/{id}', [TeacherController::class, 'update']);
    Route::get('admin/teacher/delete/{id}', [TeacherController::class, 'delete']);

    // ClassController
    Route::get('admin/class/list', [ClassController::class, 'list']);
    Route::get('admin/class/add', [ClassController::class, 'add']);
    Route::post('admin/class/add', [ClassController::class, 'insert']);
    Route::get('admin/class/edit/{id}', [ClassController::class, 'edit']);
    Route::post('admin/class/edit/{id}', [ClassController::class, 'update']);
    Route::get('admin/class/delete/{id}', [ClassController::class, 'delete']);
    Route::get('admin/class/students/{id}', [ClassController::class, 'viewStudents']);
    Route::get('admin/class/subjects/{id}', [ClassController::class, 'viewSubjects']);
    Route::get('admin/class/assign_subject/{id}', [AssignClassTeacherController::class, 'editAssignSubjectFromClass']);
    Route::post('admin/class/assign_subject/{id}', [AssignClassTeacherController::class, 'UpdateAssignSubjectFromClass']);
    // SubjectController
    Route::get('admin/subject/list', [SubjectController::class, 'list']);
    Route::get('admin/subject/add', [SubjectController::class, 'add']);
    Route::post('admin/subject/add', [SubjectController::class, 'insert']);
    Route::get('admin/subject/edit/{id}', [SubjectController::class, 'edit']);
    Route::post('admin/subject/edit/{id}', [SubjectController::class, 'update']);
    Route::get('admin/subject/delete/{id}', [SubjectController::class, 'delete']);
    // assign_subject
    Route::get('admin/assign_subject/list', [ClassSubjectController::class, 'list']);
    Route::get('admin/assign_subject/add', [ClassSubjectController::class, 'add']);
    Route::post('admin/assign_subject/add', [ClassSubjectController::class, 'insert']);
    Route::get('admin/assign_subject/edit/{id}', [ClassSubjectController::class, 'edit']);
    Route::post('admin/assign_subject/edit/{id}', [ClassSubjectController::class, 'update']);
    Route::get('admin/assign_subject/delete/{id}', [ClassSubjectController::class, 'delete']);
    Route::get('admin/assign_subject/edit_single/{id}', [ClassSubjectController::class, 'edit_single']);
    Route::post('admin/assign_subject/edit_single/{id}', [ClassSubjectController::class, 'update_single']);
    // assign_class_teacher
    Route::get('admin/assign_class_teacher/list', [AssignClassTeacherController::class, 'list']);
    Route::get('admin/assign_class_teacher/add', [AssignClassTeacherController::class, 'add']);
    Route::post('admin/assign_class_teacher/add', [AssignClassTeacherController::class, 'insert']);
    Route::get('admin/assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'edit']);
    Route::post('admin/assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'update']);
    Route::get('admin/assign_class_teacher/delete/{id}', [AssignClassTeacherController::class, 'delete']);
    Route::get('admin/assign_class_teacher/edit_single/{id}', [AssignClassTeacherController::class, 'edit_single']);
    Route::post('admin/assign_class_teacher/edit_single/{id}', [AssignClassTeacherController::class, 'update_single']);

    // class_timetable
    Route::get('admin/class_timetable/list', [ClassTimeTableController::class, 'list']);
    Route::post('admin/class_timetable/get_subject', [ClassTimeTableController::class, 'get_subject']);
    Route::post('admin/class_timetable/add', [ClassTimeTableController::class, 'insert_update']);

    // Examinatoin
    // exam
    Route::get('admin/examination/exam/list', [ExaminationController::class, 'exam_list']);
    Route::get('admin/examination/exam/add', [ExaminationController::class, 'exam_add']);
    Route::post('admin/examination/exam/add', [ExaminationController::class, 'exam_insert']);
    Route::get('admin/examination/exam/edit/{id}', [ExaminationController::class, 'exam_edit']);
    Route::post('admin/examination/exam/edit/{id}', [ExaminationController::class, 'exam_update']);
    Route::get('admin/examination/exam/delete/{id}', [ExaminationController::class, 'exam_delete']);
    // exam schedule admin/examination/exam_schedule
    Route::get('admin/examination/exam_schedule', [ExaminationController::class, 'exam_schedule']);
    Route::post('admin/examination/exam_schedule_insert', [ExaminationController::class, 'exam_schedule_insert']);
    Route::get('admin/examination/exam_schedule/delete/{id}', [ExaminationController::class, 'examDateDelete']);
    // marks_register
    Route::get('admin/examination/marks_register', [ExaminationController::class, 'marksRegister']);
    Route::post('admin/examination/submit_marks_register', [ExaminationController::class, 'submitMarksRegister']);
    // Mark Grade
    Route::get('admin/examination/marks_grade/list', [ExaminationController::class, 'marks_grade_list']);
    Route::get('admin/examination/marks_grade/add', [ExaminationController::class, 'marks_grade_add']);
    Route::post('admin/examination/marks_grade/add', [ExaminationController::class, 'marks_grade_insert']);
    Route::get('admin/examination/marks_grade/edit/{id}', [ExaminationController::class, 'marks_grade_edit']);
    Route::post('admin/examination/marks_grade/edit/{id}', [ExaminationController::class, 'marks_grade_update']);
    Route::get('admin/examination/marks_grade/delete/{id}', [ExaminationController::class, 'marks_grade_delete']);
    // Library
    Route::get('admin/library/book/list', [LibraryController::class, 'bookList']);
    Route::get('admin/library/book/add', [LibraryController::class, 'bookAdd']);
    Route::post('admin/library/book/add', [LibraryController::class, 'bookInsert']);
    Route::get('admin/library/book/edit/{id}', [LibraryController::class, 'bookEdit']);
    Route::post('admin/library/book/edit/{id}', [LibraryController::class, 'bookUpdate']);
    Route::get('admin/library/book/delete/{id}', [LibraryController::class, 'bookDelete']);

    // Issues
    Route::get('admin/library/issue/list', [LibraryController::class, 'issueList']);
    Route::get('admin/library/issue/add', [LibraryController::class, 'issueAdd']);
    Route::post('admin/library/issue/add', [LibraryController::class, 'issueInsert']);
    Route::get('admin/library/issue/edit/{id}', [LibraryController::class, 'issueEdit']);
    Route::get('admin/library/issue/return/{id}', [LibraryController::class, 'returnForm']);
    Route::post('admin/library/issue/return/{id}', [LibraryController::class, 'returnBook']);
    Route::get('admin/library/issue/delete/{id}', [LibraryController::class, 'issueDelete']);

    // Fine
    Route::get('admin/library/fine/list', [LibraryController::class, 'fineList']);
    Route::get('admin/library/fine/collect/{id}', [LibraryController::class, 'fineCollect']);
    Route::post('admin/library/fine/collect/{id}', [LibraryController::class, 'fineCollectSubmit']);
    Route::post('admin/library/fine/waive/{id}', [LibraryController::class, 'fineWaive']);
    Route::get('admin/library/fine/report', [LibraryController::class, 'fineReport']);

    // Attendance
    // student attendance
    Route::get('admin/attendance/student_attendance', [AttendanceController::class, 'studentAttendance']);
    Route::post('admin/attendance/student_attendance_save', [AttendanceController::class, 'studentAttendanceSave']);
    // Attendance Report
    Route::get('admin/attendance/attendance_report', [AttendanceController::class, 'attendanceReport']);
    // Teacher
    Route::get('admin/attendance/teacher_attendance', [TeacherAttendanceController::class, 'index']);
    Route::post('admin/attendance/teacher_attendance/save', [TeacherAttendanceController::class, 'save']);
    // Attendance Report
    Route::get('admin/attendance/teacher_attendance_report', [TeacherAttendanceController::class, 'report']);

    // communicate notice_board
    Route::get('admin/communicate/notice_board', [CommunicateController::class, 'noticeBoard']);
    Route::get('admin/communicate/notice_board/add', [CommunicateController::class, 'addNoticeBoard']);
    Route::post('admin/communicate/notice_board/add', [CommunicateController::class, 'insertNoticeBoard']);
    Route::get('admin/communicate/notice_board/edit/{id}', [CommunicateController::class, 'editNoticeBoard']);
    Route::post('admin/communicate/notice_board/edit/{id}', [CommunicateController::class, 'updateNoticeBoard']);
    Route::get('admin/communicate/notice_board/delete/{id}', [CommunicateController::class, 'deleteNoticeBoard']);
    // Send Emails
    Route::get('admin/communicate/send_email', [CommunicateController::class, 'sendEmail']);
    Route::get('admin/communicate/search_user', [CommunicateController::class, 'searchEmail']);
    Route::post('admin/communicate/send_email', [CommunicateController::class, 'sendEmailUser']);
    // Home Work
    Route::get('admin/homework/homework', [HomeworkController::class, 'homework']);
    Route::get('admin/homework/homework/add', [HomeworkController::class, 'add']);
    Route::post('admin/ajax_get_subject', [HomeworkController::class, 'ajax_get_subject']);
    Route::post('admin/homework/homework/add', [HomeworkController::class, 'insert']);
    Route::get('admin/homework/homework/edit/{id}', [HomeworkController::class, 'edit']);
    Route::post('admin/homework/homework/edit/{id}', [HomeworkController::class, 'update']);
    Route::get('admin/homework/homework/delete/{id}', [HomeworkController::class, 'delete']);
    Route::get('admin/homework/homework/submitted/{id}', [HomeworkController::class, 'submitted']);
    Route::get('admin/homework/homework_report', [HomeworkController::class, 'homeworkReport']);
    // Fee Types
    Route::get('admin/fee_type/list', [FeeTypeController::class, 'list']);
    Route::get('admin/fee_type/add', [FeeTypeController::class, 'add']);
    Route::post('admin/fee_type/add', [FeeTypeController::class, 'insert']);
    Route::get('admin/fee_type/edit/{id}', [FeeTypeController::class, 'edit']);
    Route::post('admin/fee_type/edit/{id}', [FeeTypeController::class, 'update']);
    Route::get('admin/fee_type/delete/{id}', [FeeTypeController::class, 'delete']);
    

    // Student Fees
    Route::get('admin/fee/list', [StudentFeeController::class, 'list']);
    Route::get('admin/fee/add', [StudentFeeController::class, 'add']);
    Route::post('admin/fee/add', [StudentFeeController::class, 'insert']);
    Route::get('admin/fee/edit/{id}', [StudentFeeController::class, 'edit']);
    Route::post('admin/fee/edit/{id}', [StudentFeeController::class, 'update']);
    Route::get('admin/fee/delete/{id}', [StudentFeeController::class, 'delete']);
    Route::get('admin/fee/collect/{id}', [StudentFeeController::class, 'collectPayment']);
    Route::get('admin/fee/payment_report', [StudentFeeController::class, 'paymentReport']);

    // My Account
    Route::get('admin/account', [UserController::class, 'MyAccount']);
    Route::post('admin/account', [UserController::class, 'update']);
    // change_password
    Route::get('admin/profile/change_password', [UserController::class, 'change_password']);
    Route::post('admin/profile/change_password', [UserController::class, 'update_change_password']);

});
// Teacher Section
Route::group(['middleware' => 'teacher'], function () {
    Route::get('/teacher/dashboard', [DashboardController::class, 'dashboard']);
    // my_class_subject
    Route::get('teacher/my_class_subject', [AssignClassTeacherController::class, 'MyClassSubject']);
    // my_student
    Route::get('teacher/my_student', [StudentController::class, 'MyStudent']);
    // my_timetable
    Route::get('teacher/my_class_subject/class_timetable/{class_id}/{subject_id}', [ClassTimeTableController::class, 'myTimetableTeacher']);
    // my_exam_timetable
    Route::get('teacher/my_exam_timetable', [ExaminationController::class, 'myExamTimetableTeacher']);
    // My Calender
    Route::get('teacher/my_calender', [CalenderController::class, 'MyTeacherCalendar']);
    //     libray
    Route::get('teacher/library/my_books', [LibraryController::class, 'myBooks']);
    Route::get('teacher/library/my_fines', [LibraryController::class, 'myFines']);
    // marks_register
    Route::get('teacher/marks_register', [ExaminationController::class, 'marksRegisterTeacher']);
    Route::post('teacher/submit_marks_register', [ExaminationController::class, 'submitMarksRegisterTeacher']);
    // student attendance
    Route::get('teacher/attendance/student_attendance', [AttendanceController::class, 'studentAttendanceTeacher']);
    Route::post('teacher/attendance/student_attendance_save', [AttendanceController::class, 'studentAttendanceSaveTeacher']);
    // Attendance Report
    Route::get('teacher/attendance/attendance_report', [AttendanceController::class, 'attendanceReportTeacher']);
    // My Notice Board
    Route::get('teacher/my_notice_board', [CommunicateController::class, 'myNoticeBoardTeacher']);
    // Home Work
    Route::get('teacher/homework/homework', [HomeworkController::class, 'homeworkTeacher']);
    Route::get('teacher/homework/homework/add', [HomeworkController::class, 'addTeacher']);
    Route::post('teacher/ajax_get_subject', [HomeworkController::class, 'ajax_get_subject']);
    Route::post('teacher/homework/homework/add', [HomeworkController::class, 'insertTeacher']);
    Route::get('teacher/homework/homework/edit/{id}', [HomeworkController::class, 'editTeacher']);
    Route::post('teacher/homework/homework/edit/{id}', [HomeworkController::class, 'updateTeacher']);
    Route::get('teacher/homework/homework/delete/{id}', [HomeworkController::class, 'deleteTeacher']);
    Route::get('teacher/homework/homework_report', [HomeworkController::class, 'homeworkReportTeacher']);
    // change_password Teacher
    Route::get('teacher/profile/change_password', [UserController::class, 'change_password']);
    Route::post('teacher/profile/change_password', [UserController::class, 'update_change_password']);
    // My Account
    Route::get('teacher/account', [UserController::class, 'MyAccount']);
    Route::post('teacher/account', [UserController::class, 'update']);

});

//  Student
Route::group(['middleware' => 'student'], function () {
    Route::get('/student/dashboard', [DashboardController::class, 'dashboard']);
    // my_subject
    Route::get('student/my_subject', [SubjectController::class, 'mySubject']);
    // my_timetable
    Route::get('student/my_timetable', [ClassTimeTableController::class, 'myTimetable']);
    // my_exam_timetable
    Route::get('student/my_exam_timetable', [ExaminationController::class, 'myExamTimetable']);
    // change_password
    Route::get('student/profile/change_password', [UserController::class, 'change_password']);
    Route::post('student/profile/change_password', [UserController::class, 'update_change_password']);
    // My Calender
    Route::get('student/my_calender', [CalenderController::class, 'MyCalender']);
    // Exam Result my_exam_result
    Route::get('student/my_exam_result', [ExaminationController::class, 'MyExamResult']);
    // my_attendance
    Route::get('student/my_attendance', [AttendanceController::class, 'studentMyAttendance']);
    Route::get('student/my_attendance_report', [AttendanceController::class, 'attendanceReportStudent']);
    // library
    Route::get('student/library/my_books', [LibraryController::class, 'myBooks']);
    Route::get('student/library/my_fines', [LibraryController::class, 'myFines']);
    // My Notice Board
    Route::get('student/my_notice_board', [CommunicateController::class, 'myNoticeBoardStudent']);
    // My Home Work
    Route::get('student/my_homework', [HomeworkController::class, 'homeworkStudent']);
    Route::get('student/my_homework/submit_homework/{id}', [HomeworkController::class, 'submitHomework']);
    Route::post('student/my_homework/submit_homework/{id}', [HomeworkController::class, 'submitHomeworkInsert']);
    Route::get('student/my_submitted_homework', [HomeworkController::class, 'submittedHomeworkStudent']);
    Route::get('student/homework/edit_submit/{id}', [HomeworkController::class, 'editHomework']); // New
    Route::post('student/homework/edit_submit/{id}', [HomeworkController::class, 'submit_homework_insert']);
    // Fees
    Route::get('student/my_fees', [StudentFeeController::class, 'myFees']);
    // My Account
    Route::get('student/account', [UserController::class, 'MyAccount']);
    Route::post('student/account', [UserController::class, 'update']);

});
//  parent
Route::group(['middleware' => 'parent'], function () {
    Route::get('/parent/dashboard', [DashboardController::class, 'dashboard']);
    // my_student
    Route::get('parent/my_student', [ParentController::class, 'myStudentParent']);
    // view student subject
    Route::get('parent/my_student/subject/{student_id}', [SubjectController::class, 'ParentStudentSubject']);
    // time table parent/my_class_subject/class_timetable/
    Route::get('parent/my_student/subject/class_timetable/{class_id}/{subject_id}/{student_id}', [ClassTimeTableController::class, 'myTimetableParent']);
    // student exam timetable
    Route::get('parent/my_student/exam_timetable/{student_id}', [ExaminationController::class, 'ParentMyExamTimetable']);
    // stuedent calendar
    Route::get('parent/my_student/calendar/{student_id}', [CalenderController::class, 'MyParentCalendar']);
    // Exam Result
    Route::get('parent/my_student/my_exam_result/{student_id}', [ExaminationController::class, 'ParentExamResult']);
    // my_attendance
    Route::get('parent/my_student/my_attendance/{student_id}', [AttendanceController::class, 'parentMyAttendance']);
    // My Notice Board
    Route::get('parent/my_notice_board', [CommunicateController::class, 'myNoticeBoardParent']);
    // home work submitted_homework
    Route::get('parent/my_student/homework/{student_id}', [HomeworkController::class, 'homeworkParent']);
    Route::get('parent/my_student/submitted_homework/{student_id}', [HomeworkController::class, 'submittedHomeworkParent']);
    // Parents
    Route::get('parent/my_student/fees/{student_id}', [StudentFeeController::class, 'parentFees']);

    // change_password
    Route::get('parent/profile/change_password', [UserController::class, 'change_password']);
    Route::post('parent/profile/change_password', [UserController::class, 'update_change_password']);
    // My Account
    Route::get('parent/account', [UserController::class, 'MyAccount']);
    Route::post('parent/account', [UserController::class, 'update']);

});
// Accountant
Route::group(['middleware' => 'accountant'], function () {
    Route::get('/accountant/dashboard', [StudentFeeController::class, 'accountantDashboard']);
    Route::get('accountant/fee/list', [StudentFeeController::class, 'accountantList']);
    Route::get('accountant/fee/collect/{id}', [StudentFeeController::class, 'collectPayment']);
    Route::post('accountant/fee/collect/{id}', [StudentFeeController::class, 'submitPayment']);
    Route::get('accountant/fee/payment_report', [StudentFeeController::class, 'paymentReport']);
    Route::get('accountant/account', [UserController::class, 'MyAccount']);
    Route::post('accountant/account', [UserController::class, 'update']);
    Route::get('accountant/profile/change_password', [UserController::class, 'change_password']);
    Route::post('accountant/profile/change_password', [UserController::class, 'update_change_password']);
});

// librabina 
Route::group(['middleware' => 'librarian'], function () {
    Route::get('/librarian/dashboard', [LibrarianController::class, 'dashboard']);  
    Route::get('librarian/my_account', [UserController::class, 'MyAccount']);
    Route::post('librarian/my_account', [UserController::class, 'update']);
    Route::get('librarian/profile/change_password', [UserController::class, 'change_password']);
    Route::post('librarian/profile/change_password', [UserController::class, 'update_change_password']);
    // library
    Route::get('librarian/library/book/list', [LibraryController::class, 'bookListLibrarian']);
    Route::get('librarian/library/book/add', [LibraryController::class, 'bookAddLibrarian']);
    Route::post('librarian/library/book/add', [LibraryController::class, 'bookInsertLibrarian']);
    Route::get('librarian/library/book/edit/{id}', [LibraryController::class, 'bookEditLibrarian']);
    Route::post('librarian/library/book/edit/{id}', [LibraryController::class, 'bookUpdateLibrarian']);
    Route::get('librarian/library/book/delete/{id}', [LibraryController::class, 'bookDeleteLibrarian']);

    // Issues
    Route::get('librarian/library/issue/list', [LibraryController::class, 'issueListLibrarian']);
    Route::get('librarian/library/issue/add', [LibraryController::class, 'issueAddLibrarian']);
    Route::post('librarian/library/issue/add', [LibraryController::class, 'issueInsertLibrarian']);
    Route::get('librarian/library/issue/edit/{id}', [LibraryController::class, 'issueEditLibrarian']);
    Route::get('librarian/library/issue/return/{id}', [LibraryController::class, 'returnFormLibrarian']);
    Route::post('librarian/library/issue/return/{id}', [LibraryController::class, 'returnBookLibrarian']);
    Route::get('librarian/library/issue/delete/{id}', [LibraryController::class, 'issueDeleteLibrarian']);
      // Fine
    Route::get('librarian/library/fine/list', [LibraryController::class, 'fineListLibrarian']);
    Route::get('librarian/library/fine/collect/{id}', [LibraryController::class, 'fineCollectLibrarian']);
    Route::post('librarian/library/fine/collect/{id}', [LibraryController::class, 'fineCollectSubmitLibrarian']);
    Route::post('librarian/library/fine/waive/{id}', [LibraryController::class, 'fineWaiveLibrarian']);   
    Route::get('librarian/library/fine/report', [LibraryController::class, 'fineReportLibrarian']);   
});
    
