<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $table = 'supervisors';
    protected $primaryKey = 'supervisor_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'department',
        'max_teams',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}