<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'vuid',
        'program',
        'semester',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}