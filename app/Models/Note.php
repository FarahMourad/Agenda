<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Note extends Model{
    protected $table = 'note';
    protected $fillable = [
        'note_id',
        'user_id',
        'title',
        'category',
        'content',
        'pinned'
    ];
}
