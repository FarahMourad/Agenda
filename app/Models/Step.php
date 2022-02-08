<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Step extends Model{
    protected $table = 'task_step';
//    public $timestamps = false;
    protected $fillable = [
        'step_id',
        'task_id',
        'content',
        'deadline',
        'completed'
    ];
}
