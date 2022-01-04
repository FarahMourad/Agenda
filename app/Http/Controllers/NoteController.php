<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Note_category;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Mockery\Matcher\Not;
use function Symfony\Component\Translation\t;

class NoteController
{
    public function getAllNotes(): JsonResponse
    {
        $user_id = auth()->user()->user_id;
        $note = Note::where([
            ['user_id', $user_id]
        ])->orderBy('id', 'ASC')->get([
            'note_id',
            'user_id',
            'title',
            'category',
            'content',
            'creation_date',
            'modified_date',
            'pinned']);
        return response()->json($note);
    }

    public function getCategoryNotes(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;
        $note = Note::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->get();
        return response()->json('notes', $note);
    }

    public function addNote(Request $request): Response
    { // title, category, content, creation_date, modified_date, pinned
        $user_id = auth()->user()->user_id;
        $title = $request->title; //not null
        $category = $request->category;
        $content = $request->note_content;
        $creation_date = $request->creation_date; //not null
        $modified_date = $request->modified_date; //not null
        $pinned = $request->pinned;
        $last_note = Note::where('user_id', $user_id)->latest('note_id')->first();
        $note_id = ($last_note != null) ? ($last_note->note_id + 1) : 1;
        if ($title != null && $creation_date != null && $modified_date != null) {
            $note = new Note();
            $note->user_id = $user_id;
            $note->note_id = $note_id;
            $note->title = $title;
            $note->category = $category;
            $note->content = $content;
            $note->creation_date = $creation_date;
            $note->modified_date = $modified_date;
            $note->pinned = !(($pinned == null) || ($pinned == false));
            $note->save();
        }
        return response()->noContent();
    }

    public function editNote(Request $request)
    { // note_id, title, content, category, modified_date, pinned
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $title = $request->title;
        $content = $request->note_content;
        $category = $request->category;
        $modified_date = $request->modified_date;
        $pinned = $request->pinned;

        if ($title == null || $modified_date == null || $note_id == null) {
            return redirect()->back()->withErrors('msg', 'ERROR: null content');
        }
        $note = Note::where([
            ['user_id', $user_id],
            ['note_id', $note_id]
        ])->first();
        $table_empty = Note_category::count();
        $is_category_found = Note_category::where([
            ['user_id', $user_id],
            ['category', $category]
        ]);
        if ($is_category_found == null || $table_empty == 0) {
            $new_category = new Note_category();
            $new_category->category = $category;
            $new_category->user_id = $user_id;
            $new_category->save();
        }
        $note->title = $title;
        $note->content = $content;
        $note->category = $category;
        $note->modified_date = $modified_date;
        $note->pinned = !(($pinned == null) || ($pinned == false));
        $note->save();
        return response()->noContent();
    }

    public function deleteNote(Request $request)
    { // note_id
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $note = Note::where([
            ['user_id', $user_id],
            ['note_id', $note_id]
        ]);
        $note->delete();
    }

    public function pinNote(Request $request)
    { // note_id, pinned
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $pinned = $request->pinned;
        $note = Note::where([
            ['user_id', $user_id],
            ['note_id', $note_id]
        ]);
        $note->pinned = !(($pinned == null) || ($pinned == false));
        $note->save();
    }

    public function shareNote(Request $request)
    { //note_id, coll_username
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $coll_username = $request->coll_username;
        $collaborator = User::where([
            ['user_id', $coll_username]
        ]);
        if ($collaborator != null) {
            $note = Note::where([
                ['user_id', $user_id],
                ['note_id', $note_id]
            ]);
            $new_note = new Note();
            $new_note = $note;
            $new_note->user_id = $coll_username;
            $last_note = Note::where('user_id', $coll_username)->latest('note_id')->first();
            $new_note->note_id = ($last_note != null) ? ($last_note->note_id + 1) : 1;
            $new_note->category = 'Shared with me';
            $new_note->save();
        }

//        $note->pinned = !(($pinned == null || $pinned == false));
        $note->save();
    }

    public function sortNotesByTitle(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;

        if ($category == 'all') {
            $retrieved_notes = Note::where([
                ['user_id', $user_id]
            ])->get([
                'note_id',
                'user_id',
                'title',
                'category',
                'content',
                'creation_date',
                'modified_date',
                'pinned']);
        } else {
            $retrieved_notes = Note::where([
                ['user_id', $user_id],
                ['category', $category]
            ])->get([
                'note_id',
                'user_id',
                'title',
                'category',
                'content',
                'creation_date',
                'modified_date',
                'pinned']);
        }
        return response()->json($retrieved_notes->sortBy("title"));
    }

    public function createNoteCategory(Request $request)
    { // category
        $user_id = auth()->user()->user_id;
        $category = $request->category;
        $is_category_found = Note_category::where([
            ['user_id', $user_id],
            ['category', $category]
        ]);
        if ($is_category_found == null) {
            $new_category = new Note_category();
            $new_category->user_id = $user_id;
            $new_category->category = $category;
            $new_category->save();
        } else {
            return redirect()->back()->withErrors('msg', 'ERROR: already exists');
        }
    }

    public function getCategories(): JsonResponse
    {
        $user_id = auth()->user()->user_id;
        $categories = Note_category::where([
            ['user_id', $user_id]
        ])->get();
        return response()->json($categories);
    }
}
