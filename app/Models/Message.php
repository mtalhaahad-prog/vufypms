<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table      = 'messages';
    protected $primaryKey = 'message_id';

    const CREATED_AT = 'sent_at';
    const UPDATED_AT = null;

    protected $fillable = ['sender_id','receiver_id','team_id','content','is_read'];
}