<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table      = 'announcements';
    protected $primaryKey = 'announcement_id';

    const CREATED_AT = 'published_at';
    const UPDATED_AT = null;

    protected $fillable = ['admin_id','title','body'];
}