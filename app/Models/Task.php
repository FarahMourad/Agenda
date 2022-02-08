<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Task extends Model{
    protected $table = 'task';
//    public $timestamps = false;
    protected $fillable = [
        'task_id',
        'user_id',
        'title',
        'category',
        'description',
        'deadline',
        'pinned',
        'completed'
    ];
}
