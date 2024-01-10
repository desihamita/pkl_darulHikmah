<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Subject;
use App\Models\ExamAttempt;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_name',
        'subject_id',
        'time',
        'date',
        'attempt',
        'token',
        'marks',
        'kelas_id',
        'status',
    ];
    protected $appends = ['attempt_counter'];
    public $count = '';

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function subjects(){
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function qnaExams(){
        return $this->hasMany(QnaExam::class, 'exam_id', 'id');
    }

    public function getIdAttribute($value){
        $attemptCount = ExamAttempt::where(['exam_id'=>$value, 'user_id' => auth()->user()->id])->count();
        $this->count = $attemptCount;
        return $value;
    }

    public function getAttemptCounterAttribute() {
        return $this->count;
    }

}