<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Task_category extends Model{
    protected $table = 'task_category';
    public $timestamps = false;
    protected $fillable = [
        'category',
        'user_id'
    ];
}
