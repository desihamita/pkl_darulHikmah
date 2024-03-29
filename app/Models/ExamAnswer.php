<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    use HasFactory;
    public $table = "exams_answers";

    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer_id'
    ];

    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id', 'id');
    }

    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id', 'id');
    }

}