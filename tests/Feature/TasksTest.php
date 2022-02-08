<?php

use App\Models\Task;
use App\Models\Task_category;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Pipeline;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function only_logged_in_users_can_see_their_tasks()
    {
        $this->get('/getAllTasks')->assertRedirect('/login');
        $this->get('/getCategoryTasks')->assertRedirect('/login');
    }

    /** @test */
    public function add_task()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Work on the SWE Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('task', [
            'task_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software'
        ]);
    }

    /** @test */
    public function some_details_can_not_be_null_when_add_task()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => null,
            'category' => 'SWE',
            'description' => 'Work on Project',
            'deadline' => '2022-01-03',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Work on the SWE Project',
            'deadline' => '2022-01-03',
            'creation_date' => null,
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software2',
            'category' => 'SWE',
            'description' => 'Work on the SWE Project',
            'creation_date' => '2022-01-03',
            'modified_date' => null,
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $this->assertDatabaseMissing('task', [
            'task_id' => 1,
            'user_id' => $user->user_id,
            'description' => 'Work on Project'
        ]);
        $this->assertDatabaseMissing('task', [
            'task_id' => 2,
            'user_id' => $user->user_id,
            'title' => 'Software'
        ]);
        $this->assertDatabaseMissing('task', [
            'task_id' => 3,
            'user_id' => $user->user_id,
            'title' => 'Software2'
        ]);
    }

    /** @test */
    public function edit_task_already_exist()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Work on SWE Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/editTask', 'POST', [
            'task_id' => 1,
            'title' => 'AI',
            'category' => 'ML',
            'description' => 'Work on AI Project',
            'deadline' => '2022-02-11',
            'modified_date' => '2022-02-08',
            'pinned' => true,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->editTask($request);
        })->assertStatus(204);

        $this->assertDatabaseMissing('task', [
            'task_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'description' => 'Work on SWE Project',
            'pinned' => false,
            'completed' => false
        ]);

        $this->assertDatabaseHas('task', [
            'task_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'AI',
            'description' => 'Work on AI Project',
            'deadline' => '2022-02-11',
            'pinned' => true,
            'completed' => false
        ]);
    }

    /** @test */
    public function some_details_can_not_be_null_when_edit_task()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Work on SWE Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/editTask', 'POST', [
            'task_id' => 1,
            'title' => null,
            'category' => null,
            'description' => 'Work on AI Project',
            'deadline' => '2022-02-11',
            'modified_date' => '2022-02-08',
            'pinned' => true
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->editTask($request);
        })->assertStatus(302);

        $this->assertDatabaseMissing('task', [
            'task_id' => 1,
            'user_id' => $user->user_id,
            'description' => 'Work on AI Project',
            'pinned' => true
        ]);

        $this->assertDatabaseHas('task', [
            'task_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'description' => 'Work on SWE Project',
            'deadline' => '2022-02-09',
            'pinned' => false
        ]);
    }

    /** @test */
    public function delete_task()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Work on SWE Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/deleteTask', 'POST', [
            'task_id' => 1
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->deleteTask($request);
        })->assertStatus(204);

        $this->assertDatabaseMissing('task', [
            'task_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Work on SWE Project',
            'pinned' => false
        ]);
    }

    /** @test */
    public function sort_by_title()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'aSoftware Engineering Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'AI',
            'category' => 'ML',
            'description' => 'gAI Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/sortTasksByTitle', 'POST', [
            'category' => 'all'
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->sortByTitle($request);
        });
        $this->assertSame(json_encode([
            [
                "task_id" => 2,
                "user_id" => $user->user_id,
                "title" => "AI",
                "category" => "ML",
                "description" => "gAI Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0,
                "completed" => 0
            ],
            [
                "task_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "description" => "aSoftware Engineering Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0,
                "completed" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function sort_by_title_with_pinned_first()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Database',
            'category' => 'DBS',
            'description' => 'Database Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2001-01-01',
            'modified_date' => '2001-01-01',
            'pinned' => true,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'aSoftware Engineering Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'AI',
            'category' => 'ML',
            'description' => 'gAI Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/sortTasksByTitle', 'POST', [
            'category' => 'all'
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->sortByTitle($request);
        });
        $this->assertSame(json_encode([
            [
                "task_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Database",
                "category" => "DBS",
                "description" => "Database Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2001-01-01",
                "modified_date" => "2001-01-01",
                "pinned" => 1,
                "completed" => 0
            ],
            [
                "task_id" => 3,
                "user_id" => $user->user_id,
                "title" => "AI",
                "category" => "ML",
                "description" => "gAI Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0,
                "completed" => 0
            ],
            [
                "task_id" => 2,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "description" => "aSoftware Engineering Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0,
                "completed" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function sort_by_deadline()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'AI',
            'category' => 'SWE',
            'description' => 'aSoftware Engineering Project',
            'deadline' => '2022-02-11',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'ML',
            'description' => 'gAI Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/sortByDeadline', 'POST', [
            'category' => 'all'
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->sortByDeadline($request);
        });
        $this->assertSame(json_encode([
            [
                "task_id" => 2,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "ML",
                "description" => "gAI Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0,
                "completed" => 0
            ],
            [
                "task_id" => 1,
                "user_id" => $user->user_id,
                "title" => "AI",
                "category" => "SWE",
                "description" => "aSoftware Engineering Project",
                "deadline" => "2022-02-11",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0,
                "completed" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function sort_by_deadline_with_pinned_first()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Database',
            'category' => 'DBS',
            'description' => 'Database Project',
            'deadline' => '2022-02-13',
            'creation_date' => '2001-01-01',
            'modified_date' => '2001-01-01',
            'pinned' => true,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'aSoftware Engineering Project',
            'deadline' => '2022-02-11',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'AI',
            'category' => 'ML',
            'description' => 'gAI Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/sortByDeadline', 'POST', [
            'category' => 'all'
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->sortByDeadline($request);
        });
        $this->assertSame(json_encode([
            [
                "task_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Database",
                "category" => "DBS",
                "description" => "Database Project",
                "deadline" => "2022-02-13",
                "creation_date" => "2001-01-01",
                "modified_date" => "2001-01-01",
                "pinned" => 1,
                "completed" => 0
            ],
            [
                "task_id" => 3,
                "user_id" => $user->user_id,
                "title" => "AI",
                "category" => "ML",
                "description" => "gAI Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0,
                "completed" => 0
            ],
            [
                "task_id" => 2,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "description" => "aSoftware Engineering Project",
                "deadline" => "2022-02-11",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0,
                "completed" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function get_all_tasks_sorted_by_date()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'aSoftware Engineering Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'AI',
            'category' => 'ML',
            'description' => 'gAI Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/getAllTasks', 'GET', [
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->getTasks();
        });
        $this->assertSame(json_encode([
            [
                "task_id" => 2,
                "user_id" => $user->user_id,
                "title" => "AI",
                "category" => "ML",
                "description" => "gAI Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0,
                "completed" => 0
            ],
            [
                "task_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "description" => "aSoftware Engineering Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0,
                "completed" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function get_all_tasks_sorted_by_date_with_pinned_first()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'aSoftware Engineering Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'AI',
            'category' => 'ML',
            'description' => 'gAI Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Database',
            'category' => 'DBS',
            'description' => 'Database Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2021-01-01',
            'modified_date' => '2021-01-01',
            'pinned' => true,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/getAllTasks', 'GET', [
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->getTasks();
        });
        $this->assertSame(json_encode([
            [
                "task_id" => 3,
                "user_id" => $user->user_id,
                "title" => "Database",
                "category" => "DBS",
                "description" => "Database Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2021-01-01",
                "modified_date" => "2021-01-01",
                "pinned" => 1,
                "completed" => 0
            ],
            [
                "task_id" => 2,
                "user_id" => $user->user_id,
                "title" => "AI",
                "category" => "ML",
                "description" => "gAI Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0,
                "completed" => 0
            ],
            [
                "task_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "description" => "aSoftware Engineering Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0,
                "completed" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function get_category_tasks()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Software Engineering Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software2',
            'category' => 'SWE',
            'description' => 'Software Engineering Project2',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/getCategoryTasks', 'GET', [
            'category' => 'SWE'
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->getCategoryTasks($request);
        });
        $this->assertSame(json_encode([
            [
                "task_id" => 2,
                "user_id" => $user->user_id,
                "title" => "Software2",
                "category" => "SWE",
                "description" => "Software Engineering Project2",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0,
                "completed" => 0
            ],
            [
                "task_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "description" => "Software Engineering Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 0,
                "completed" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function get_category_tasks_with_pinned_first()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Software Engineering Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => true,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software2',
            'category' => 'SWE',
            'description' => 'Software Engineering Project2',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-05',
            'modified_date' => '2022-01-05',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software3',
            'category' => 'SWE',
            'description' => 'Software Engineering Project3',
            'deadline' => '2022-02-09',
            'creation_date' => '2021-01-01',
            'modified_date' => '2021-01-01',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/getCategoryTasks', 'GET', [
            'category' => 'SWE'
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->getCategoryTasks($request);
        });
        $this->assertSame(json_encode([
            [
                "task_id" => 1,
                "user_id" => $user->user_id,
                "title" => "Software",
                "category" => "SWE",
                "description" => "Software Engineering Project",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-03",
                "modified_date" => "2022-01-03",
                "pinned" => 1,
                "completed" => 0
            ],
            [
                "task_id" => 2,
                "user_id" => $user->user_id,
                "title" => "Software2",
                "category" => "SWE",
                "description" => "Software Engineering Project2",
                "deadline" => "2022-02-09",
                "creation_date" => "2022-01-05",
                "modified_date" => "2022-01-05",
                "pinned" => 0,
                "completed" => 0
            ],
            [
                "task_id" => 3,
                "user_id" => $user->user_id,
                "title" => "Software3",
                "category" => "SWE",
                "description" => "Software Engineering Project3",
                "deadline" => "2022-02-09",
                "creation_date" => "2021-01-01",
                "modified_date" => "2021-01-01",
                "pinned" => 0,
                "completed" => 0
            ]
        ]), $response->getContent(), '');
    }

    /** @test */
    public function pin_task()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Software Engineering Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/pinTask', 'POST', [
            'task_id' => 1,
            'pinned' => true
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->setAsPinned($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('task', [
            'task_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Software Engineering Project',
            'deadline' => '2022-02-09',
            'pinned' => true
        ]);
    }

    /** @test */
    public function create_category()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/createTaskCategory', 'POST', [
            'category' => 'SWE',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->createCategory($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('task_category', [
            'category' => 'SWE',
            'user_id' => $user->user_id
        ]);
    }

    /** @test */
    public function share_task()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $user2 = UserFactory::new()->create();

        $request = Request::create('/addTask', 'POST', [
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Software Engineering Project',
            'deadline' => '2022-02-09',
            'creation_date' => '2022-01-03',
            'modified_date' => '2022-01-03',
            'pinned' => false,
            'completed' => false
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->addTask($request);
        })->assertStatus(204);

        $request = Request::create('/shareAsCopy', 'POST', [
            'task_id' => 1,
            'collaborator_username' => $user2->user_id
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->shareAsCopy($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('task', [
            'task_id' => 1,
            'user_id' => $user->user_id,
            'title' => 'Software',
            'category' => 'SWE',
            'description' => 'Software Engineering Project',
            'deadline' => '2022-02-09'
        ]);

        $this->assertDatabaseHas('task', [
            'task_id' => 1,
            'user_id' => $user2->user_id,
            'title' => 'Software',
            'category' => 'Shared with me',
            'description' => 'Software Engineering Project',
            'deadline' => '2022-02-09'
        ]);
    }

    /** @test */
    public function get_categories_of_specific_user()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/createTaskCategory', 'POST', [
            'category' => 'SWE',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->createCategory($request);
        })->assertStatus(204);

        $request = Request::create('/createTaskCategory', 'POST', [
            'category' => 'ML',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->handleRequestUsing($request, function ($request) {
            return $this->createCategory($request);
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
    public function addTask(Request $request): Response// title, category, description, deadline, pinned, completed
    {
        $user_id = auth()->user()->user_id;
        if($request->title != null && $request->creation_date != null && $request->modified_date != null){
            $task = new Task();
            $task->creation_date = $request->creation_date; //not null
            $task->modified_date = $request->modified_date; //not null
            $task->user_id = $user_id;
            $task->title = $request->title;
            $task->category = $request->category;
            $task->description = $request->description;
            $task->deadline = $request->deadline;
            $task->pinned = $request->pinned;
            $task->completed = $request->completed;
            $last_task = Task::where('user_id', $user_id)->latest('task_id')->first();
            $task_id = ($last_task != null) ? ($last_task->task_id + 1) : 1;
            $task->task_id =$task_id;
            $task->save();
        }
        return response()->noContent();
    }

    public function editTask(Request $request) // task_id, title, category, description, deadline, pinned, completed
    {
        $user_id = auth()->user()->user_id;
        $task_id = $request->task_id;
        $title = $request->title;
        $category = $request->category;
        $description = $request->description;
        $deadline = $request->deadline;
        $pinned = $request->pinned;
        $completed = $request->completed;

        if ($title == null || $task_id == null) {
            return redirect()->back()->withErrors('msg', 'ERROR: null content');
        }
        $task = Task::where([
            ['user_id', $user_id],
            ['task_id', $task_id]
        ])->first();
        $table_empty = Task_category::count();
        $is_category_found = Task_category::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->first();
        if ($is_category_found == null || $table_empty == 0) {
            $new_category = new Task_category();
            $new_category->category = $category;
            $new_category->user_id = $user_id;
            $new_category->save();
        }
        $task->title = $title;
        $task->category = $category;
        $task->description = $description;
        $task->deadline = $deadline;
        $task->pinned = !(($pinned == null) || ($pinned == false));
        $task->completed = !(($completed == null) || ($completed == false));
        $task->save();
        return response()->noContent();

    }

    public function editTitle(Request $request) // task_id, title
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id],
        ])->first();
        $task->title = $request->title;
        $task->save();
    }

    public function editDeadline(Request $request) // task_id, deadline
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id],
        ])->first();
        $task->deadline = $request->deadline;
        $task->save();
    }

    public function editDescription(Request $request) // task_id, description
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id],
        ])->first();
        $task->description = $request->description;
        $task->save();
    }

    public function deleteTask(Request $request): Response
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['user_id', $user_id],
            ['task_id', $request->task_id]
        ]);
        $task->delete();
        return response()->noContent();
    }

    public function createCategory(Request $request): Response // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;
        $is_category_found = Task_category::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->first();

        if ($is_category_found == null) {
            $new_category = new Task_category();
            $new_category->user_id = $user_id;
            $new_category->category = $category;
            $new_category->save();
        }
        return response()->noContent();
    }

    public function getCategoryTasks(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;
        $tasks = Task::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->orderBy('pinned', 'DESC')->orderBy('modified_date', 'DESC')->get([
            'task_id',
            'user_id',
            'title',
            'category',
            'description',
            'deadline',
            'creation_date',
            'modified_date',
            'pinned',
            'completed']);
        return response()->json($tasks);
    }

    public function editCategory(Request $request): Response // task_id, category
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id]
        ])->first();
        $task->category = $request->category;
        $task->save();
        return response()->noContent();
    }

    public function setAsPinned(Request $request): Response // task_id, pinned
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id],
        ])->first();
        $task->pinned = $request->pinned;
        $task->save();
        return response()->noContent();
    }

    public function sortByTitle(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;

        if ($category == 'all') {
            $retrieved_tasks = Task::where([
                ['user_id', $user_id]
            ])->orderBy('pinned', 'DESC')->orderBy('title', 'ASC')->get([
                'task_id',
                'user_id',
                'title',
                'category',
                'description',
                'deadline',
                'creation_date',
                'modified_date',
                'pinned',
                'completed']);
        } else {
            $retrieved_tasks = Task::where([
                ['user_id', $user_id],
                ['category', $category]
            ])->orderBy('pinned', 'DESC')->orderBy('title', 'ASC')->get([
                'task_id',
                'user_id',
                'title',
                'category',
                'description',
                'deadline',
                'creation_date',
                'modified_date',
                'pinned',
                'completed']);
        }
        return response()->json($retrieved_tasks);
    }

    public function sortByDeadline(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;

        if ($category == 'all') {
            $retrieved_tasks = Task::where([
                ['user_id', $user_id]
            ])->orderBy('pinned', 'DESC')->orderBy('deadline', 'ASC')->get([
                'task_id',
                'user_id',
                'title',
                'category',
                'description',
                'deadline',
                'creation_date',
                'modified_date',
                'pinned',
                'completed']);
        } else {
            $retrieved_tasks = Task::where([
                ['user_id', $user_id],
                ['category', $category]
            ])->orderBy('pinned', 'DESC')->orderBy('deadline', 'ASC')->get([
                'task_id',
                'user_id',
                'title',
                'category',
                'description',
                'deadline',
                'creation_date',
                'modified_date',
                'pinned',
                'completed']);
        }
        return response()->json($retrieved_tasks);
    }

    public function getTasks(): JsonResponse
    {
        $user_id = auth()->user()->user_id;
        $tasks = Task::where([
            ['user_id', $user_id]
        ])->orderBy('pinned', 'DESC')->orderBy('modified_date', 'DESC')->get([
            'task_id',
            'user_id',
            'title',
            'category',
            'description',
            'deadline',
            'creation_date',
            'modified_date',
            'pinned',
            'completed']);
        return response()->json($tasks);
    }

    public function shareAsCopy(Request $request) //task_id, collaborator_username
    {
        $user_id = auth()->user()->user_id;
        $task_id = $request->task_id;
        $collaborator = User::where([
            ['user_id', $request->collaborator_username]
        ])->first();
        if ($collaborator != null) {
            $task = Task::where([
                ['user_id', $user_id],
                ['task_id', $task_id]
            ])->first();
            $new_task = new Task();
            $new_task->user_id = $request->collaborator_username;
            $new_task->title = $task->title;
            $last_task = Task::where('user_id', $request->collaborator_username)->latest('task_id')->first();
            $new_task->task_id = ($last_task != null) ? ($last_task->task_id + 1) : 1;
            $new_task->category = 'Shared with me';
            //to be deleted
            $new_task->description = $task->description;
            $new_task->deadline = $task->deadline;
            $new_task->creation_date = $task->creation_date;
            $new_task->modified_date = $task->modified_date;

            $new_task->pinned = false;
            $new_task->completed = false;
            $new_task->save();
            return response()->noContent();
        }
    }

    public function getCategories(): JsonResponse
    {
        $user_id = auth()->user()->user_id;
        $categories = Task_category::where([
            ['user_id', $user_id]
        ])->get();
        return response()->json($categories);
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
