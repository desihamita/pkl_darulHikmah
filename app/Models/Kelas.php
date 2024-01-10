<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    public $table = "class";
    protected $fillable = [
        'class',
        'semester'
    ];

    public function student() {
        return $this->hasMany(User::class, 'kelas_id', 'id');
    }
    
    public function exam() {
        return $this->hasMany(Exam::class, 'kelas_id', 'id');
    } 
    
    public function question() {
        return $this->hasMany(Question::class, 'kelas_id', 'id');
    }
}