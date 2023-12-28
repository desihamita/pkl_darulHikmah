<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'question'
    ];

    public function answers() {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    public function subjects() {
        return $this->hasMany(Subject::class, 'question_id', 'id');
    }
}
