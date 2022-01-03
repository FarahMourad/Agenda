<?php

use App\Models\Note;
use App\Models\Note_category;
use App\Models\Note_collaborator;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class NotesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function only_logged_in_users_can_see_their_notes()
    {
//        $this->get('/') -> assertRedirect('/login');
    }

    /**
     * Handle Request using the following pipeline.
     *
     * @param Request $request
     * @param  callable  $callback
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
