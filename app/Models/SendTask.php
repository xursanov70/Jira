<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_name',
        'category_name',
        'description',
        'high',
        'user_id',
        'partner_id',
        'original_task',
    ];
}
