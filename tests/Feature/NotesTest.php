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
            'note_content' => 'Work on the SWE Project',
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
    public function some_details_can_not_be_null_when_add_note()
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
    public function some_details_can_not_be_null_when_edit_note()
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
            'title' => null,
            'category' => null,
            'note_content' => 'Work on AI Project',
            'modified_date' => '2022-01-04',
            'pinned' => true
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->editNote($request);
        })->assertStatus(302);

        $this->assertDatabaseMissing('note', [
            'note_id' => 1,
            'user_id' => $user->user_id,
            'content' => 'Work on AI Project',
            'pinned' => true
        ]);

        $this->assertDatabaseHas('note', [
            'note_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'content' => 'Work on SWE Project',
            'pinned' => false
        ]);
    }

    /** @test */
    public function delete_note()
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

        $request = Request::create('/deleteNote', 'POST', [
            'note_id' => 1
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->deleteNote($request);
        })->assertStatus(204);

        $this->assertDatabaseMissing('note', [
            'note_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'category' => 'SWE',
            'content' => 'Work on SWE Project',
            'pinned' => false
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
    public function sort_by_title_with_pinned_first()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Database',
            'category' => 'DBS',
            'note_content' => 'Database Project',
            'creation_date' => '2001-01-01',
            'modified_date' => '2001-01-01',
            'pinned' => true
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'aSoftware Engineering Project',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
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
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
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
                "note_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Database",
                "category" => "DBS",
                "content" => "Database Project",
                "creation_date" => "2001-01-01",
                "modified_date" => "2001-01-01",
                "pinned" => 1
            ],
            [
                "note_id" => 3,
                "user_id" => $user->user_id,
                "title" => "AI",
                "category" => "ML",
                "content" => "gAI Project",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0
            ],
            [
                "note_id" => 2,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "content" => "aSoftware Engineering Project",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function get_all_notes_sorted_by_date()
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
    public function get_all_notes_sorted_by_date_with_pinned_first()
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

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Database',
            'category' => 'DBS',
            'note_content' => 'Database Project',
            'creation_date' => '2021-01-01',
            'modified_date' => '2021-01-01',
            'pinned' => true
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
                "note_id" => 3,
                "user_id" => $user->user_id,
                "title" => "Database",
                "category" => "DBS",
                "content" => "Database Project",
                "creation_date" => "2021-01-01",
                "modified_date" => "2021-01-01",
                "pinned" => 1
            ],
            [
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
    public function get_category_notes()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'Software Engineering Project',
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
            'title' => 'Software2',
            'category' => 'SWE',
            'note_content' => 'Software Engineering Project2',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/getCategoryNotes', 'GET', [
            'category' => 'SWE'
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->getCategoryNotes($request);
        });
        $this->assertSame(json_encode([
            [
                "note_id" => 2,
                "user_id" => $user->user_id,
                "title" => "Software2",
                "category" => "SWE",
                "content" => "Software Engineering Project2",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0
            ],
            [
                "note_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "content" => "Software Engineering Project",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function get_category_notes_with_pinned_first()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'Software Engineering Project',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => true
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software2',
            'category' => 'SWE',
            'note_content' => 'Software Engineering Project2',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software3',
            'category' => 'SWE',
            'note_content' => 'Software Engineering Project3',
            'creation_date' => '2021-01-01',
            'modified_date' => '2021-01-01',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/getCategoryNotes', 'GET', [
            'category' => 'SWE'
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->getCategoryNotes($request);
        });
        $this->assertSame(json_encode([
            [
                "note_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "content" => "Software Engineering Project",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 1
            ],
            [
                "note_id" => 2,
                "user_id" => $user->user_id,
                "title" => "Software2",
                "category" => "SWE",
                "content" => "Software Engineering Project2",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0
            ],
            [
                "note_id" => 3,
                "user_id" => $user->user_id,
                "title" => "Software3",
                "category" => "SWE",
                "content" => "Software Engineering Project3",
                "creation_date" => "2021-01-01",
                "modified_date" => "2021-01-01",
                "pinned" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function pin_note()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'Software Engineering Project',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/pinNote', 'POST', [
            'note_id' => 1,
            'pinned' => true
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->pinNote($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('note', [
            'note_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'category' => 'SWE',
            'content' => 'Software Engineering Project',
            'pinned' => true
        ]);
    }

    /** @test */
    public function create_category()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/createNoteCategory', 'POST', [
            'category' => 'SWE',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->createNoteCategory($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('note_category', [
            'category' => 'SWE',
            'user_id' => $user->user_id
        ]);
    }

    /** @test */
    public function share_note()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $user2 = UserFactory::new()->create();

        $request = Request::create('/addNote', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'note_content' => 'Software Engineering Project',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addNote($request);
        })->assertStatus(204);

        $request = Request::create('/shareNote', 'POST', [
            'note_id' => 1,
            'coll_username' => $user2->user_id
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->shareNote($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('note', [
            'note_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'category' => 'SWE',
            'content' => 'Software Engineering Project',
        ]);

        $this->assertDatabaseHas('note', [
            'note_id' => 1,
            'user_id' => $user2->user_id,
            'title' => 'Software',
            'category' => 'Shared with me',
            'content' => 'Software Engineering Project',
        ]);
    }

    /** @test */
    public function get_categories_of_specific_user()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/createNoteCategory', 'POST', [
            'category' => 'SWE',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->createNoteCategory($request);
        })->assertStatus(204);

        $request = Request::create('/createNoteCategory', 'POST', [
            'category' => 'ML',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->createNoteCategory($request);
        })->assertStatus(204);

        $response = $this->getCategories();

        $this->assertSame(json_encode([
            [
                "category" => "SWE",
                "user_id" => $user->user_id
            ],
            [
                "category" => "ML",
                "user_id" => $user->user_id
            ]
        ]), $response->getContent(), '');
    }


    ##################fuctions to test##################
    public function getAllNotes(): JsonResponse
    {
        $user_id = auth()->user()->user_id;
        $note = Note::where([
            ['user_id', $user_id]
        ])->orderBy('pinned', 'DESC')->orderBy('modified_date', 'DESC')->get([
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

    public function sortNotesByTitle(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;

        if ($category == 'all') {
            $retrieved_notes = Note::where([
                ['user_id', $user_id]
            ])->orderBy('pinned', 'DESC')->orderBy('title', 'ASC')->get([
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
            ])->orderBy('pinned', 'DESC')->orderBy('title', 'ASC')->get([
                'note_id',
                'user_id',
                'title',
                'category',
                'content',
                'creation_date',
                'modified_date',
                'pinned']);
        }
        return response()->json($retrieved_notes);
    }

    public function deleteNote(Request $request): Response
    { // note_id
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $note = Note::where([
            ['user_id', $user_id],
            ['note_id', $note_id]
        ]);
        $note->delete();
        return response()->noContent();
    }

    public function getCategoryNotes(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;
        $note = Note::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->orderBy('pinned', 'DESC')->orderBy('modified_date', 'DESC')->get([
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

    public function createNoteCategory(Request $request)
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
            return response()->noContent();
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

    public function pinNote(Request $request): Response
    { // note_id, pinned
        $user_id = auth()->user()->user_id;
        $note_id = $request->note_id;
        $pinned = $request->pinned;
        $note = Note::where([
            ['user_id', $user_id],
            ['note_id', $note_id]
        ])->first();
        $note->pinned = !(($pinned == null) || ($pinned == false));
        $note->save();
        return response()->noContent();
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
            ])->first();
            $new_note = new Note();
            //may generate error
            $new_note->user_id = $coll_username;
            $new_note->title = $note->title;
            $new_note->content = $note->content;
            $last_note = Note::where('user_id', $coll_username)->latest('note_id')->first();
            $new_note->note_id = ($last_note != null) ? ($last_note->note_id + 1) : 1;
            $new_note->category = 'Shared with me';
            //to be deleted
            $new_note->creation_date = $note->creation_date;
            $new_note->modified_date = $note->modified_date;
            $new_note->pinned = false;
            echo $new_note;
            $new_note->save();
            return response()->noContent();
        }
//        $note->pinned = !(($pinned == null || $pinned == false));
//        $note->save();
    }

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
