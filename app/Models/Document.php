<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table      = 'documents';
    protected $primaryKey = 'document_id';

    const CREATED_AT = 'uploaded_at';
    const UPDATED_AT = null;

    protected $fillable = ['team_id','milestone_id','doc_type','file_path'];
}