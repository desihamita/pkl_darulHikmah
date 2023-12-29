<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Subject;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_name',
        'subject_id',
        'time',
        'date',
        'attempt'
    ];

    public function subjects(){
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
    
    public function QnaExams(){
        return $this->hasMany(QnaExam::class, 'exam_id', 'id');
    }
}