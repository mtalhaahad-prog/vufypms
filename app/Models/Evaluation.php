<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table      = 'evaluations';
    protected $primaryKey = 'eval_id';

    const CREATED_AT = 'evaluated_at';
    const UPDATED_AT = null;

    protected $fillable = ['team_id','supervisor_id','phase','marks','remarks'];
}