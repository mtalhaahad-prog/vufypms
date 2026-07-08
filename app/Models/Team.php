<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table      = 'teams';
    protected $primaryKey = 'team_id';
    public $timestamps    = false;

    protected $fillable = ['supervisor_id','group_code','team_name','status'];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'supervisor_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class, 'team_id', 'team_id');
    }

    public function proposal()
    {
        return $this->hasOne(Proposal::class, 'team_id', 'team_id');
    }
}