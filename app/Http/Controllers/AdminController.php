<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subject;
use App\Models\Kelas;
use App\Models\Exam;
// user
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Imports\QnaImport;
use App\Imports\UserImport;
// qna
use App\Models\Question;
use App\Models\Answer;
use App\Models\QnaExam;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;

use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    // Subject
    public function subjectDashboard(Request $request){
        $subjects = Subject::query();

        if ($request->get('search')) {
            $subjects = $subjects->where('subject', 'ILIKE', '%' . $request->get('search') . '%');
        }

        $subjects = $subjects->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc')->get();

        return view('admin.subject-dashboard', compact(
            'subjects',
            'request'
        ));
    }
    public function createSubject(Request $request){
        try {
            Subject::insert([
                'subject' => $request->subject,
            ]);

            return response()->json(['success' => true, 'msg' => 'Subject added Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function updateSubject(Request $request){
        try {
            $subject = Subject::find($request->id);
            $subject->subject = $request->subject;
            $subject->save();

            return response()->json(['success' => true, 'msg' => 'Subject updated Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function deleteSubject(Request $request){
        try {
            Subject::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Subject deleted Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }

    // Class
    public function classDashboard(Request $request){
        $kelas = Kelas::query();

        if ($request->get('search')) {
            $kelas = $kelas->where('class', 'ILIKE', '%' . $request->get('search') . '%');
        }

        $kelas = $kelas->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc')->get();

        return view('admin.kelas-dashboard', compact('kelas', 'request'));
    }
    public function createClass(Request $request){
        try {
            Kelas::insert([
                'class' => $request->kelas,
                'semester' => $request->semester
            ]);

            return response()->json(['success' => true, 'msg' => 'Class added Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function updateClass(Request $request){
        try {
            $kelas = Kelas::find($request->id);
            $kelas->class = $request->kelas;
            $kelas->semester = $request->semester;
            $kelas->save();

            return response()->json(['success' => true, 'msg' => 'Kelas updated Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function deleteClass(Request $request){
        try {
            Kelas::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Class deleted Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }

    //Exam
    public function examDashboard(Request $request){
        $exams = Exam::query();
        $subjects = Subject::all();
        $kelas = Kelas::all();

        if ($request->get('search')) {
            $searchTerm = $request->get('search');
            $exams->where(function ($query) use ($searchTerm) {
                $query->where('exam_name', 'ILIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('subjects', function ($query) use ($searchTerm) {
                        $query->where('subject', 'ILIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        $exams = $exams->with('subjects', 'kelas')
                   ->orderBy('created_at', 'desc')
                   ->orderBy('updated_at', 'desc')
                   ->get();

        return view('admin.exam-dashboard', compact(
            'subjects',
            'exams',
            'kelas',
            'request'
        ));
    }
    public function createExam(Request $request){
        try {
            Exam::insert([
                'token' => strtoupper(Str::random(8)),
                'exam_name' => $request->exam_name,
                'subject_id' => $request->subject_id,
                'kelas_id' => $request->kelas_id,
                'time' => $request->time,
                'date' => $request->date,
                'attempt' => $request->attempt
            ]);

            return response()->json(['success' => true, 'msg' => 'Exam Added Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function getExamDetail($id){
        try {
            $exam = Exam::where('id', $id)->get();

            return response()->json(['success' => true, 'data' => $exam]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function updateExam(Request $request){
        try {
            $exam = Exam::find($request->exam_id);
            $exam->exam_name = $request->exam_name;
            $exam->subject_id = $request->subject_id;
            $exam->kelas_id = $request->kelas_id;
            $exam->date = $request->date;
            $exam->time = $request->time;
            $exam->attempt = $request->attempt;
            $exam->save();

            return response()->json(['success' => true, 'msg' => 'Exam updated Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function updateStatus($id) {
        $exam = Exam::findOrFail($id);
        $exam->status = !$exam->status;
        $exam->save();

        return response()->json(['status' => $exam->status]);
    }

    public function deleteExam(Request $request){
        try {
            Exam::where('id', $request->exam_id)->delete();

            return response()->json(['success' => true, 'msg' => 'Exam deleted Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }

    // QnA
    public function qnaDashboard(Request $request){
        $questions = Question::query();
        $subjects = Subject::all();
        $kelas = Kelas::all();

        if ($request->get('search')) {
            $searchTerm = $request->get('search');
            $questions->where(function ($query) use ($searchTerm) {
                $query->where('question', 'ILIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('subjects', function ($query) use ($searchTerm) {
                        $query->where('subject', 'ILIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        $questions = $questions->with('answers', 'kelas', 'subjects')
                                ->orderBy('created_at', 'desc')
                                ->orderBy('updated_at', 'desc')
                                ->get();

        return view('admin.qna-dashboard', compact(
            'questions',
            'subjects',
            'kelas',
            'request'
        ));

    }
    public function createQna(Request $request){
        try {
            $explanation = null;

            if (isset($request->explanation)) {
                $explanation = $request->explanation;
            }

            $questionId = Question::insertGetId([
                'kelas_id' => $request->kelas_id,
                'subject_id' => $request->subject_id,
                'question' => $request->question,
                'explanation' => $explanation
            ]);

            foreach ($request->answers as $answer) {
                $is_correct = 0;
                if ($request->is_correct == $answer) {
                    $is_correct = 1;
                }

                Answer::insert([
                    'question_id' => $questionId,
                    'answer' => $answer,
                    'is_correct' => $is_correct
                ]);
            }

            return response()->json(['success' => true, 'msg' => "Qna Added Successfully!"]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function getQnaDetails(Request $request){
        $qna = Question::where('id', $request->question_id)->with('answers')->get();
        return response()->json(['data' => $qna]);
    }
    public function deleteAns(Request $request){
        Answer::where('id', $request->id)->delete();
        return response()->json(['success' => true, 'msg'=> "Answer Deleted Successfully!"]);
    }
    public function updateQna(Request $request){
        try {
            $explanation = null;

            if (isset($request->explanation)) {
                $explanation = $request->explanation;
            }

            Question::where('id', $request->question_id)->update([
                'kelas_id' => $request->kelas_id,
                'subject_id' => $request->subject_id,
                'question' => $request->question,
                'explanation' => $explanation,

            ]);

            if (isset($request->answers)) {
                foreach ($request->answers as $key => $value) {
                    $is_correct = 0;

                    if ($request->is_correct == $value) {
                        $is_correct = 1;
                    }

                    Answer::where('id', $key)->update([
                        'question_id' => $request->question_id,
                        'answer' => $value,
                        'is_correct' => $is_correct
                    ]);
                }
            }

            //new answer added
            if (isset($request->new_answers)) {
                foreach ($request->new_answers as $answer) {
                    $is_correct = 0;

                    if ($request->is_correct == $answer) {
                        $is_correct = 1;
                    }

                    Answer::insert([
                        'question_id' => $request->question_id,
                        'answer' => $answer,
                        'is_correct' => $is_correct
                    ]);
                }
            }

            return response()->json(['success' => true, 'msg' => 'Qna updated Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function deleteQna(Request $request){
        Question::where('id', $request->id)->delete();
        Answer::where('question_id', $request->id)->delete();

        return response()->json(['success' => true, 'msg' => 'Qna Deleted Successfully!']);
    }
    public function importQna(Request $request){
        try {
            Excel::import(new QnaImport, $request->file('file'));
            return response()->json(['success'=> true,'msg'=> 'Import Qna Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }

    // students
    public function studentDashboard(Request $request){
        $students = User::query();
        $kelas = Kelas::all();

        if ($request->get('search')) {
            $students = $students->where('name', 'ILIKE', '%' . $request->get('search') . '%')
            ->orWhere('nis', 'ILIKE', '%' . $request->get('search') . '%')
            ->orWhere('email', 'ILIKE', '%' . $request->get('search') . '%');
        }

        $students = $students->with('kelas')
                             ->where('is_admin', 0)
                             ->orderBy('created_at', 'desc')
                             ->orderBy('updated_at', 'desc')
                             ->get();

        return view('admin.student-dashboard', compact(
            'students',
            'kelas',
            'request'
        ));
    }
    public function createStudent(Request $request){
        try {
            User::insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis
            ]);

            return response()->json(['success' => true, 'msg' => 'Student added Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function updateStudent(Request $request){
        try {
            $student = User::find($request->id);
            $student->nis = $request->nis;
            $student->name = $request->nama;
            $student->kelas_id = $request->kelas_id;
            $student->email = $request->email;
            $student->save();

            return response()->json(['success' => true, 'msg' => 'Student updated Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function deleteStudent(Request $request){
        try {
            User::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Student deleted Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function importStudent(Request $request){
        try {
            Excel::import(new UserImport, $request->file('file'));

            return response()->json(['success'=> true,'msg'=> 'Import Student Successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }

    // qna exams
    public function getQuestions(Request $request){
        try {
            $questions = Question::all();

            if (count($questions) > 0) {
                $data = [];
                $counter = 0;

                foreach ($questions as $question) {
                    $qnaExam = QnaExam::where([
                        'exam_id' => $request->exam_id,
                        'question_id' => $question->id
                    ])->get();

                    if (count($qnaExam) == 0) {
                        $data[$counter]['id'] = $question->id;
                        $data[$counter]['questions'] = $question->question;
                        $data[$counter]['subjects'] = $question->subject_id;
                        $counter++;
                    }
                }
                return response()->json(['success'=> true,'msg'=> 'Questions data!', 'data' => $data]);
            } else {
                return response()->json(['success'=> false,'msg'=> 'Questions Not Found!']);
            }
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function addQuestions(Request $request){
        try {
            if(isset($request->questions_ids)){
                foreach ($request->questions_ids as $qid) {
                    QnaExam::insert([
                        'exam_id' => $request->exam_id,
                        'question_id' => $qid
                    ]);
                }
            }
            return response()->json(['success' => true, 'msg' => 'Questions added successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage]);
        }
    }
    public function getExamQuestions(Request $request){
        try {
            $data = QnaExam::where('exam_id', $request->exam_id)->with('question')->get();
            return response()->json(['success' => true, 'msg' => 'Questions details!', 'data' => $data]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function deleteExamQuestions(Request $request){
        try {
            QnaExam::where('id', $request->id)->delete();
            return response()->json(['success' => true, 'msg' => 'Questions deleted successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }

    // Marks
    public function loadMarks(){
        $exams = Exam::with('qnaExams')->orderBy('created_at', 'desc')
                                       ->orderBy('updated_at', 'desc')
                                       ->get();
        return view('admin.marks-dashboard', compact('exams'));
    }
    public function updateMarks(Request $request){
        try {
            Exam::where('id', $request->exam_id)->update([
                'marks' => $request->marks,
                'pass_marks' => $request->pass_marks
            ]);
            return response()->json(['success' => true, 'msg' => 'Marks updated successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }

    // reviewsExams
    public function reviewExams(Request $request){
        $attemps = ExamAttempt::query();
                                                    
        $exam = Exam::all();
        $user = User::all();

        if ($request->get('search')) {
            $searchTerm = $request->get('search');
            $attemps->where(function ($query) use ($searchTerm) {
                $query->orWhereHas('exam', function ($query) use ($searchTerm) {
                        $query->where('exam_name', 'ILIKE', '%' . $searchTerm . '%');
                    })->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('name', 'ILIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        $attemps = $attemps->with('user', 'exam', 'examAnswer')->orderBy('id')
        ->orderBy('created_at', 'desc')
        ->orderBy('updated_at', 'desc')
        ->get();
                   
        return view('admin.review-exams', compact('attemps', 'request'));
    }
    public function reviewQna(Request $request){
        try {
            $attempData = ExamAnswer::where('attempt_id', $request->attempt_id)->with(['question', 'answer'])->get();
            return response()->json(['success' => true, 'msg' => $attempData]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
    public function approvedQna(Request $request){
        try {
            $attemptId = $request->attempt_id;

            $examData = ExamAttempt::where('id', $attemptId)->with('exam')->get();
            $marks = $examData[0]['exam']['marks'];

            $attempData = ExamAnswer::where('attempt_id', $attemptId)->with('answer')->get();

            $totalMarks = 0;

            if (count($attempData) > 0) {
                foreach ($attempData as $attempt) {
                    if($attempt->answer->is_correct == 1){
                        $totalMarks += $marks;
                    }
                }
            }

            ExamAttempt::where('id', $attemptId)->update([
                'status' => 1,
                'marks' => $totalMarks
            ]);

            return response()->json(['success' => true, 'msg' => 'Approved Successfully!', 'data' => $attempData]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }
}