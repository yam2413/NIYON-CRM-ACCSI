<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmLeads extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id',
        'file_id',
        'status',
        'priority',
        'idle',
        'assign_user',
        'assign_group',
        'payment_date',
        'ptp_amount',
        'account_number',
        'endo_date',
        'due_date',
        'loan_amount',
        'outstanding_balance',
    ];
}
