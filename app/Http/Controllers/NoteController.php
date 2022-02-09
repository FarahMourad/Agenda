<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Note_category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NoteController
{
    public function getAllNotes(): JsonResponse
    {
        $user_id = auth()->user()->user_id;
        $note = Note::where([
            ['user_id', $user_id]
        ])->orderBy('pinned', 'DESC')->orderBy('updated_at', 'DESC')->get([
            'note_id',
            'user_id',
            'title',
            'category',
            'content',
            'created_at',
            'updated_at',
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
        return response()->json($note);
    }

    public function addNote(Request $request): RedirectResponse
    { // title, category, content, creation_date, modified_date, pinned
        $user_id = auth()->user()->user_id;
        $title = $request->title; //not null
        $category = $request->category;
        $content = $request->note_content;
        $pinned = $request->pinned;
        $last_note = Note::where('user_id', $user_id)->latest('note_id')->first();
        $note_id = ($last_note != null) ? ($last_note->note_id + 1) : 1;
        if ($title != null) {
            $note = new Note();
            $note->user_id = $user_id;
            $note->note_id = $note_id;
            $note->title = $title;
            $note->category = $category;
            $note->content = $content;
            $note->pinned = !(($pinned == null) || ($pinned == false));
            $note->save();
        }
        return redirect()->back();
    }

    public function editNote(Request $request): RedirectResponse
    { // note_id, title, content, category, modified_date, pinned
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $title = $request->title;
        $content = $request->note_content;
        $category = $request->category;
        $pinned = $request->pinned;

        if ($title == null || $note_id == null) {
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
        $note->pinned = !(($pinned == null) || ($pinned == false));
        $note->save();
        return redirect()->back();
    }

    public function deleteNote(Request $request): RedirectResponse
    { // note_id
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $note = Note::where([
            ['user_id', $user_id],
            ['note_id', $note_id]
        ]);
        $note->delete();
        return redirect()->back();
    }

    public function pinNote(Request $request)
    { // note_id, pinned
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $note = Note::where([
            ['user_id', $user_id],
            ['note_id', $note_id]
        ])->first();
        if($note->pinned){
            $note->pinned = false;
        } else{
            $note->pinned = true;
        }
        $note->save();
    }

    public function shareNote(Request $request)
    { //note_id, coll_username
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $coll_username = $request->coll_username;
        $collaborator = User::where([
            ['user_id', $coll_username]
        ])->first();
        if ($collaborator != null) {
            $note = Note::where([
                ['user_id', $user_id],
                ['note_id', $note_id]
            ])->first();
            $new_note = new Note();
            $new_note->user_id = $coll_username;
            $new_note->title = $note->title;
            $new_note->content = $note->content;
            $last_note = Note::where('user_id', $coll_username)->latest('note_id')->first();
            $new_note->note_id = ($last_note != null) ? ($last_note->note_id + 1) : 1;
            $new_note->category = 'Shared with me';
            $new_note->pinned = false;
            $new_note->save();
            return response()->json(1);
        }
        return response()->json(0);
    }

    public function sortNotesByTitle(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;

        if ($category == 'All') {
            $retrieved_notes = Note::where([
                ['user_id', $user_id]
            ])->orderBy('pinned', 'DESC')->orderBy('title', 'ASC')->get([
                'note_id',
                'user_id',
                'title',
                'category',
                'content',
                'pinned']);
        } else {
            $retrieved_notes = Note::where([
                ['user_id', $user_id],
                ['category', $category]
            ])->orderBy('pinned', 'DESC')->orderBy('title', 'ASC')->get([
                'note_id',
                'user_id',
                'title',
                'category',
                'content',
                'pinned']);
        }
        return response()->json($retrieved_notes);
    }

    public function createNoteCategory(Request $request): JsonResponse
    { // category
        $user_id = auth()->user()->user_id;
        $category = $request->category;
        $is_category_found = Note_category::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->first();
        if ($is_category_found == null) {
            $new_category = new Note_category();
            $new_category->user_id = $user_id;
            $new_category->category = $category;
            $new_category->save();
            return response()->json(1);
        } else {
            return response()->json(0);
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
