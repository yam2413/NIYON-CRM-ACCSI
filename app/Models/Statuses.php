<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statuses extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'status_name',
        'group',
        'description',
        'added_by',
    ];
}
