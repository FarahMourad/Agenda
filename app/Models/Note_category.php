<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Note_category extends Model{
    protected $table = 'note_category';
//    public $timestamps = false;
    protected $fillable = [
        'category',
        'user_id'
    ];
}
