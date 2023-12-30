<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Exam;

class StudentController extends Controller
{
    public function checkToken(Request $request){
        $submittedToken = $request->input('token');
        $exam = DB::table('exams')->where('token', $submittedToken)->first();

        if ($exam) {
            return redirect()->route('studentUjian');
        } else {
            return redirect()->back()->with('message', 'Token tidak sesuai. Harap coba lagi.');
        }
    }

    public function studentUjian(){
        return view('student.ujian');
    }
}