<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Task_collaborator extends Model{
    protected $table = 'task_collaborator';
    public $timestamps = false;
    protected $fillable = [
        'task_id',
        'collaborator_id'
    ];
}
