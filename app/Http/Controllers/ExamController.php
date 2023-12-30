<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Exam;
use App\Models\QnaExam;

class ExamController extends Controller
{
    public function checkToken(Request $request){
        $submittedToken = $request->input('token');
        $exam = Exam::where('token', $submittedToken)->first();

        if ($exam) {
            return redirect()->route('loadExamDashboard', ['id' => $exam->token]);
        } else {
            return redirect()->back()->with('message', 'Token tidak sesuai. Harap coba lagi.');
        }
    }

    public function loadExamDashboard($id){
        $qnaExam = Exam::where('token',$id)->with('qnaExams')->inRandomOrder()->get();
        if (count($qnaExam) > 0) {
            if ($qnaExam[0]['date'] == date('Y-m-d')) {
                if(count($qnaExam[0]['qnaExams']) > 0) {
                    $qna = QnaExam::where('exam_id', $qnaExam[0]['id'])->with('question', 'answers')->get();

                    return view('student.exam-dashboard', ['success' => true,'exam'=>$qnaExam, 'qna'=>$qna]);
                } else {
                    return view('student.exam-dashboard', ['success' => false, 'msg' => 'This exam is not available for now','exam'=>$qnaExam]);
                }
            } elseif($qnaExam[0]['date'] > date('Y-m-d')) {
                return view('student.exam-dashboard', ['success' => false, 'msg' => 'This exam will be start on '.$qnaExam[0]['date'], 'exam'=>$qnaExam]);
            } else {
                return view('student.exam-dashboard', ['success' => false, 'msg' => 'This exam has been expired on '.$qnaExam[0]['date'], 'exam'=>$qnaExam]);
            }
        } else {
            return view('404');
        }
    }
}