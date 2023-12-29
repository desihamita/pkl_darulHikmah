<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'subject_id'
    ];

    public function answers() {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    public function subjects(){
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function QnaExams(){
        return $this->hasMany(QnaExam::class, 'question_id', 'id');
    }
}