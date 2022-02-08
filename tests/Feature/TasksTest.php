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



    ##################fuctions to test##################
    public function addTask(Request $request): Response// title, category, description, deadline, pinned, completed
    {
        $user_id = auth()->user()->user_id;
        echo $user_id;
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
            echo $task;
            $task->save();
        }
        return response()->noContent();
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
