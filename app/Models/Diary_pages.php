<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Diary_pages extends Model{
    protected $table = 'diary_page';
    public $timestamps = false;
    protected $fillable = [
        'page_id',
        'user_id',
        'content',
        'bookmarked',
    ];
}
