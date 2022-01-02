<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Note_category;
use App\Models\Note_collaborator;
use http\Env\Response;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
class NoteController
{
    public function addNote(Request $request){ //note_id

    }

    public function deleteNote(Request $request){ //note_id

    }

    public function setTitle(Request $request){ //note_id, title

    }

    public function setContent(Request $request){ //note_id, content

    }

    public function setCategory(Request $request){ //note_id, category

    }

    public function pinNote(){ //note_id

    }

    public function shareNote(Request $request){ //note_id, collaborator, asCopy

    }

    public function sortNotesByTitle(Request $request){

    }

    public function createNoteCategory(Request $request){ //title

    }

    public function editNote(Request $request){ //details

    }
}
