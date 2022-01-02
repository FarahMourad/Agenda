<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Note_collaborator extends Model{
    protected $table = 'note_collaborator';
//    public $timestamps = false;
    protected $fillable = [
        'note_id',
        'collaborator_id'
    ];
}
