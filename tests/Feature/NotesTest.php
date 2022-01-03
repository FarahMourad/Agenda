<?php

use App\Models\Note;
use App\Models\Note_category;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Pipeline;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class NotesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function only_logged_in_users_can_see_their_notes()
    {
        $this->get('/getAllNotes')->assertRedirect('/login');
        $this->get('/getCategoryNotes')->assertRedirect('/login');
    }

    /** @test */
    public function add_note()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'Work on the F***** SWE Project',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('note', [
            'note_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software'
        ]);
    }

    /** @test */
    public function some_details_can_not_be_null()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addNote', 'POST', [
            'title' => null,
            'category' => 'SWE',
            'note_content' => 'Work on Project',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'Work on the SWE Project',
            'creation_date' => null,
            'modified_date' => '2022-01-03',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software2',
            'category' => 'SWE',
            'note_content' => 'Work on the SWE Project',
            'creation_date' => '2022-01-03',
            'modified_date' => null,
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $this->assertDatabaseMissing('note', [
            'note_id' => 1,
            'user_id' => $user->user_id,
            'content' => 'Work on Project'
        ]);
        $this->assertDatabaseMissing('note', [
            'note_id' => 2,
            'user_id' => $user->user_id,
            'title' => 'Software'
        ]);
        $this->assertDatabaseMissing('note', [
            'note_id' => 3,
            'user_id' => $user->user_id,
            'title' => 'Software2'
        ]);
    }

    /** @test */
    public function edit_note_already_exist()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'Work on SWE Project',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/editNote', 'POST', [
            'note_id' => 1,
            'title' => 'AI',
            'category' => 'ML',
            'note_content' => 'Work on AI Project',
            'modified_date' => '2022-01-04',
            'pinned' => true
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->editNote($request);
        })->assertStatus(204);

        $this->assertDatabaseMissing('note', [
            'note_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'content' => 'Work on SWE Project',
            'pinned' => false
        ]);

        $this->assertDatabaseHas('note', [
            'note_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'AI',
            'content' => 'Work on AI Project',
            'pinned' => true
        ]);
    }

    /** @test */
    public function sort_by_title()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'aSoftware Engineering Project',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'AI',
            'category' => 'ML',
            'note_content' => 'gAI Project',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/sortNotesByTitle', 'POST', [
            'category' => 'all'
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->sortNotesByTitle($request);
        });
        $this->assertSame(json_encode([
            [
                //"id" => 107,
                "note_id" => 2,
                "user_id" => $user->user_id,
                "title" => "AI",
                "category" => "ML",
                "content" => "gAI Project",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0
            ],
            [
                //"id" => 106,
                "note_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "content" => "aSoftware Engineering Project",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function get_notes_sorted_by_date()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'aSoftware Engineering Project',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'AI',
            'category' => 'ML',
            'note_content' => 'gAI Project',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/getNotes', 'GET', [
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->getAllNotes();
        });
        $this->assertSame(json_encode([
            [
                //"id" => 116,
                "note_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "content" => "aSoftware Engineering Project",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0
            ],
            [
                //"id" => 117,
                "note_id" => 2,
                "user_id" => $user->user_id,
                "title" => "AI",
                "category" => "ML",
                "content" => "gAI Project",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0
            ]
        ]), $response->getContent(), '');
    }
    ##################fuctions to test##################
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
//            echo $new_category;
            $new_category->save();
            echo $new_category;
            echo "test";
        }
        $note->title = $title;
        $note->content = $content;
        $note->category = $category;
        $note->modified_date = $modified_date;
        $note->pinned = !(($pinned == null) || ($pinned == false));
        echo $note;
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

//    public function sortNotesByDate(): JsonResponse
//    {
//        $user_id = auth()->user()->user_id;
//        $sorted_notes = Note::where([
//            ['user_id', $user_id]
//        ])->orderBy('id', 'ASC')->get();
//        return response()->json($sorted_notes);
//    }


//    public function getNotes(): JsonResponse
//    {
//        $user_id = auth()->user()->user_id;
//        $sorted_notes = Note::where([
//            ['user_id', $user_id]
//        ])->orderBy('id', 'ASC')->get();
//        return response()->json($sorted_notes);
//    }

    /**
     * Handle Request using the following pipeline.
     *
     * @param Request $request
     * @param callable $callback
     * @return TestResponse
     */
    protected function handleRequestUsing(Request $request, callable $callback): TestResponse
    {
        return new TestResponse(
            (new Pipeline($this->app))
                ->send($request)
                ->through([
                    \Illuminate\Session\Middleware\StartSession::class,
                ])
                ->then($callback)
        );
    }
}
