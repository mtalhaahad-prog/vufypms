<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    protected $table      = 'presentations';
    protected $primaryKey = 'presentation_id';
    public $timestamps    = false;

    protected $fillable = ['team_id','phase','scheduled_at','venue_or_link','panel_info','status'];
}