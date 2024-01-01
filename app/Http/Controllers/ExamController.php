<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Exam;
use App\Models\QnaExam;
use App\Models\QnaEx;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;

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
        $qnaExam = Exam::where('token', $id)->with('qnaExams')->get();
        if (count($qnaExam) > 0) {
            
            $attemptCount = ExamAttempt::where(['exam_id'=> $qnaExam[0]['id'], 'user_id' => auth()->user()->id])->count();

            if ($attemptCount >= $qnaExam[0]['attempt']) {
                return view('student.exam-dashboard', ['success' => false, 'msg' => 'Your exam attempt has been completed', 'exam' => $qnaExam]);
            } else if ($qnaExam[0]['date'] == date('Y-m-d')) {
                if (count($qnaExam[0]['qnaExams']) > 0) {
                    $qna = QnaExam::where('exam_id', $qnaExam[0]['id'])->with('question', 'answers')->get();

                    $shuffledQna = $qna->shuffle();

                    return view('student.exam-dashboard', ['success' => true, 'exam' => $qnaExam, 'qna' => $shuffledQna]);
                } else {
                    return view('student.exam-dashboard', ['success' => false, 'msg' => 'This exam is not available for now', 'exam' => $qnaExam]);
                }
            } elseif ($qnaExam[0]['date'] > date('Y-m-d')) {
                return view('student.exam-dashboard', ['success' => false, 'msg' => 'This exam will start on ' . $qnaExam[0]['date'], 'exam' => $qnaExam]);
            } else {
                return view('student.exam-dashboard', ['success' => false, 'msg' => 'This exam has expired on ' . $qnaExam[0]['date'], 'exam' => $qnaExam]);
            }
        } else {
            return view('404');
        }
    }

    public function examSubmit(Request $request){
        $attempt_id = ExamAttempt::insertGetId([
            'exam_id' => $request->exam_id,
            'user_id' => Auth::user()->id
        ]);
        $qcount = count($request->q);
        if($qcount > 0){
            for($i=0; $i<$qcount; $i++){
                $questionId = $request->q[$i];
                $answerId = $request->input('ans_'.$questionId);

                if (!empty($request->input('ans_'.$questionId))) {
                    ExamAnswer::insert([
                        'attempt_id' => $attempt_id,
                        'question_id' => $questionId,
                        'answer_id' => $answerId
                    ]);
                }
            }

        }
        return view('thank-you');
    }
}