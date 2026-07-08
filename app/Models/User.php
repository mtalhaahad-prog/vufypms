<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $keyType = 'int';
    public $incrementing = true;

    // remember_token column use nahi kar rahe
    protected $rememberTokenName = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'notification_prefs',
    ];

    protected $hidden = ['password'];

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'user_id');
    }

    public function supervisor()
    {
        return $this->hasOne(Supervisor::class, 'user_id', 'user_id');
    }
}