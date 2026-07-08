<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $table      = 'team_members';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = ['team_id','student_id','role'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }
}