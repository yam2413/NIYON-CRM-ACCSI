<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUploadLogs extends Model
{
    use HasFactory;
    protected $fillable = [
        'user',
        'upload_type',
        'file_id',
        'path',
        'status',
    ];
}
