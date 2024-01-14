<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'answer',
        'is_correct'
    ];

    public function examAnswer()
    {
        return $this->hasOne(ExamAnswer::class, 'answer_id', 'id');
    }
}