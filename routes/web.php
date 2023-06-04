<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\examController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* test for pushing the changes to the github*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register',[AuthController::class,'loadRegister']);
Route::post('/register',[AuthController::class,'studentRegister'])->name('studentRegister');

Route::get('/login',function(){
    return redirect ('/');
});

Route::get('/', [AuthController::class,'loadLogin']);
Route::post('/login',[AuthController::class,'userLogin'])->name('userLogin');

Route::get('/logout', [AuthController::class,'logout']);

Route::get('/forget-password', [AuthController::class,'forgetPasswordLoad']);
Route::post('/forget-password', [AuthController::class,'forgetPassword'])->name('forgetPassword');

Route::get('/reset-password', [AuthController::class,'resetPasswordLoad']);
Route::post('/reset-password', [AuthController::class,'resetPassword'])->name('resetPassword');

Route::group(['middleware'=>['web','checkAdmin']], function(){
    Route::get('/admin/dashboard', [AuthController::class,'adminDashboard']);

    //subject route
    Route::post('/add-subject',[AdminController::class,'addSubject'])->name('addSubject');
    Route::post('/edit-subject',[AdminController::class,'editSubject'])->name('editSubject');
    Route::post('/delete-subject',[AdminController::class,'deleteSubject'])->name('deleteSubject');

    //Exam Route
    Route::get('/admin/exam',[AdminController::class,'examDashboard']);
    Route::post('/add-exam',[AdminController::class,'addExam'])->name('addExam');
    Route::get('/get-exam-detail/{id}',[AdminController::class,'getExamDetail'])->name('getExamDetail');
    Route::post('/update-exam',[AdminController::class,'updateExam'])->name('updateExam');
    Route::post('/delete-exam',[AdminController::class,'deleteExam'])->name('deleteExam');

    //Question and Answers Route
    Route::get('/admin/qna-ans',[AdminController::class,'qnaDashboard']);
    Route::post('/add-qna-ans',[AdminController::class,'addQna'])->name('addQna');
    Route::get('/get-qna-details',[AdminController::class,'getQnaDetails'])->name('getQnaDetails');
    Route::get('/delete-ans',[AdminController::class,'deleteAns'])->name('deleteAns');
    Route::post('/update-qna-ans',[AdminController::class,'updateQna'])->name('updateQna');
    Route::post('/delete-qna-ans',[AdminController::class,'deleteQna'])->name('deleteQna');

    //Students routing 
    Route::get('/admin/students',[AdminController::class,'studentsDashboard']);
    Route::post('/add-student',[AdminController::class,'addStudent'])->name('addStudent');
    Route::post('/edit-student',[AdminController::class,'editStudent'])->name('editStudent');
    Route::post('/delete-student',[AdminController::class,'deleteStudent'])->name('deleteStudent');

    //question and answer and exams routing
    Route::get('/get-questions',[AdminController::class,'getQuestions'])->name('getQuestions');
    Route::post('/add-questions',[AdminController::class,'addQuestions'])->name('addQuestions');
    Route::get('/get-exam-questions',[AdminController::class,'getExamQuestions'])->name('getExamQuestions');
    Route::get('/delete-exam-questions',[AdminController::class,'deleteExamQuestions'])->name('deleteExamQuestions');
    
    
    //exam marks routing
    Route::get('/admin/marks',[AdminController::class,'loadMarks']);
    Route::post('/update-marks',[AdminController::class,'updateMarks'])->name('updateMarks');

    // exams review routing
    Route::get('/admin/review-exams',[AdminController::class,'reviewExams'])->name('reviewExams');
    Route::get('/get-reviewed-qna',[AdminController::class,'reviewQna'])->name('reviewQna');

    Route::post('/approved-qna',[AdminController::class,'approvedQna'])->name('approvedQna');


});

    Route::group(['middleware'=>['web','checkStudent']], function(){
    Route::get('/dashboard', [AuthController::class,'loadDashboard']); 
    Route::get('/exam/{id}', [examController::class,'loadExamDashboard']); 


    Route::post('/exam-submit',[ExamController::class,'examSubmit'])->name('examSubmit');
});


