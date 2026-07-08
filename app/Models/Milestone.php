<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $table      = 'milestones';
    protected $primaryKey = 'milestone_id';
    public $timestamps    = false;

    protected $fillable = ['proposal_id','title','due_date','status'];
}