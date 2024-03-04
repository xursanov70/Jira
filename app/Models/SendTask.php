<?php

namespace App\Models;

use App\Notifications\SendTaskNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SendTask extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'task_name',
        'category_name',
        'description',
        'high',
        'user_id',
        'partner_id',
        'original_task',
        'title',
        'send_time',
        'last_task_id'
    ];

    
}
