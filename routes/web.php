<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

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

Route::get('/forget-password', [AuthController::class, 'forgetPasswordLoad']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->name('forgetPassword');

Route::group(['middleware' => ['web', 'checkAdmin']], function() {
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard']);

    //subject route
    Route::post('/create-subject', [AdminController::class, 'createSubject'])->name('createSubject');
    Route::post('/update-subject', [AdminController::class, 'updateSubject'])->name('updateSubject');
    Route::post('/delete-subject', [AdminController::class, 'deleteSubject'])->name('deleteSubject');

    //exam route
    Route::get('/admin/exam', [AdminController::class, 'examDashboard']);
    Route::post('/create-exam', [AdminController::class, 'createExam'])->name('createExam');
    Route::get('/get-exam-detail/{id}', [AdminController::class, 'getExamDetail'])->name('getExamDetail');
    Route::post('/update-exam', [AdminController::class, 'updateExam'])->name('updateExam');
    Route::post('/delete-exam', [AdminController::class, 'deleteExam'])->name('deleteExam');

    // Q&A Route
    Route::get('/admin/qna-ans', [AdminController::class, 'qnaDashboard']);
    Route::post('/create-qna-ans', [AdminController::class, 'createQna'])->name('createQna');
    Route::get('/get-qna-details', [AdminController::class, 'getQnaDetails'])->name('getQnaDetails');
    Route::get('/delete-ans', [AdminController::class, 'deleteAns'])->name('deleteAns');
    Route::post('/update-qna-ans', [AdminController::class, 'updateQna'])->name('updateQna');
    Route::post('/delete-qna-ans', [AdminController::class, 'deleteQna'])->name('deleteQna');
    Route::post('/import-qna-ans', [AdminController::class, 'importQna'])->name('importQna');

});

Route::group(['middleware' => ['web', 'checkStudent']], function() {
    Route::get('/dashboard', [AuthController::class, 'loadDashboard']);
});