<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_name',
        'description',
        'category_name',
        'start_task',
        'end_task',
        'original_task',
        'high',
        'status'
    ];
}
