<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'loadRegister']);
Route::post('/register', [AuthController::class, 'studentRegister'])->name('studentRegister');

Route::get('/login', function(){
    return redirect('/');
});

Route::get('/', [AuthController::class, 'loadLogin']);
Route::post('/login', [AuthController::class, 'userLogin'])->name('userLogin');

Route::get('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['web', 'checkAdmin']], function() {
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard']);

    //subject route
    Route::get('/admin/subject', [AdminController::class, 'subjectDashboard'])->name('subjectDashboard');
    Route::post('/create-subject', [AdminController::class, 'createSubject'])->name('createSubject');
    Route::post('/update-subject', [AdminController::class, 'updateSubject'])->name('updateSubject');
    Route::post('/delete-subject', [AdminController::class, 'deleteSubject'])->name('deleteSubject');

    //exam route
    Route::get('/admin/exam', [AdminController::class, 'examDashboard'])->name('examDashboard');
    Route::post('/create-exam', [AdminController::class, 'createExam'])->name('createExam');
    Route::get('/get-exam-detail/{id}', [AdminController::class, 'getExamDetail'])->name('getExamDetail');
    Route::post('/update-exam', [AdminController::class, 'updateExam'])->name('updateExam');
    Route::post('/delete-exam', [AdminController::class, 'deleteExam'])->name('deleteExam');

    // Q&A Route
    Route::get('/admin/qna-ans', [AdminController::class, 'qnaDashboard'])->name('qnaDashboard');
    Route::post('/create-qna-ans', [AdminController::class, 'createQna'])->name('createQna');
    Route::get('/get-qna-details', [AdminController::class, 'getQnaDetails'])->name('getQnaDetails');
    Route::get('/delete-ans', [AdminController::class, 'deleteAns'])->name('deleteAns');
    Route::post('/update-qna-ans', [AdminController::class, 'updateQna'])->name('updateQna');
    Route::post('/delete-qna-ans', [AdminController::class, 'deleteQna'])->name('deleteQna');
    Route::post('/import-qna-ans', [AdminController::class, 'importQna'])->name('importQna');

    // students routing
    Route::get('/admin/students', [AdminController::class, 'studentDashboard'])->name('studentDashboard');
    Route::post('/create-student', [AdminController::class, 'createStudent'])->name('createStudent');
    Route::get('/get-student-details', [AdminController::class, 'getStudentDetails'])->name('getStudentDetails');
    Route::post('/update-student', [AdminController::class, 'updateStudent'])->name('updateStudent');
    Route::post('/delete-student', [AdminController::class, 'deleteStudent'])->name('deleteStudent');
    Route::post('/import-student', [AdminController::class, 'importStudent'])->name('importStudent');

    //Qna Exam Routing
    Route::get('/get-questions', [AdminController::class, 'getQuestions'])->name('getQuestions');
    Route::post('/add-questions', [AdminController::class, 'addQuestions'])->name('addQuestions');
    Route::get('/get-exam-questions', [AdminController::class, 'getExamQuestions'])->name('getExamQuestions');
    Route::get('/delete-exam-questions', [AdminController::class, 'deleteExamQuestions'])->name('deleteExamQuestions');

    // Marks routing
    Route::get('/admin/marks', [AdminController::class, 'loadMarks']);
    Route::post('/update-marks', [AdminController::class, 'updateMarks'])->name('updateMarks');

    // Exams routing
    Route::get('/admin/review-exams', [AdminController::class, 'reviewExams'])->name('reviewExams');
    Route::get('/get-reviewed-qna', [AdminController::class, 'reviewQna'])->name('reviewQna');
    Route::post('/approved-qna', [AdminController::class, 'approvedQna'])->name('approvedQna');

});

Route::group(['middleware' => ['web', 'checkStudent']], function() {
    Route::get('/dashboard', [AuthController::class, 'loadDashboard']);

    Route::post('/check-token', [ExamController::class, 'checkToken'])->name('check.token');
    Route::get('/exam/{id}', [ExamController::class, 'loadExamDashboard'])->name('loadExamDashboard');

    Route::post('/exam-submit', [ExamController::class, 'examSubmit'])->name('examSubmit');

});