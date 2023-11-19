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
        return $this->hasMany(Subject::class, 'id', 'subject_id');
    }
}