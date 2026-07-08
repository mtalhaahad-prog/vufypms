<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $table      = 'proposals';
    protected $primaryKey = 'proposal_id';

    const CREATED_AT = 'submitted_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = ['team_id','title','abstract','domain','tools','status','supervisor_remarks'];

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'proposal_id', 'proposal_id');
    }
}