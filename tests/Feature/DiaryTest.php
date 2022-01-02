<?php

use App\Models\Diary_pages;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DiaryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function only_logged_in_users_can_see_their_diary()
    {
        $response = $this->get('/getDiary') -> assertRedirect('/login');
    }

    /** @test
     */
    public function create_page_and_set_content()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 3,
            'pageContent' => 'Sara',
            'bookmarked' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('diary_page', [
            'page_id' => 2,
            'user_id' => $user->user_id,
            'content' => 'Sara',
        ]);
    }

    /** @test
     */
    public function content_can_not_be_null()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 3,
            'pageContent' => null,
            'bookmarked' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $this->assertDatabaseMissing('diary_page', [
            'page_id' => 2,
            'user_id' => $user->user_id,
            'content' => 'Hi, Iam not here',
        ]);
    }

    /** @test
     */
    public function edit_page_already_exist()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 3,
            'pageContent' => 'Farah Mourad',
            'bookmarked' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('diary_page', [
            'page_id' => 2,
            'user_id' => $user->user_id,
            'content' => 'Farah Mourad',
            'bookmarked' => false,
        ]);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 3,
            'pageContent' => 'Farah Mourad & Sara Mahmoud',
            'bookmarked' => true,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $this->assertDatabaseHas('diary_page', [
            'page_id' => 2,
            'user_id' => $user->user_id,
            'content' => 'Farah Mourad & Sara Mahmoud',
            'bookmarked' => true,
        ]);
    }

    /** @test
     */
    public function delete_whole_diary()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 3,
            'pageContent' => 'Farah Mourad',
            'bookmarked' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 5,
            'pageContent' => 'Sara Mahmoud',
            'bookmarked' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $this->deleteDiary();

        $this->assertDatabaseMissing('diary_page', [
            'page_id' => 2,
            'user_id' => $user->user_id,
            'content' => 'Farah Mourad',
        ]);
        $this->assertDatabaseMissing('diary_page', [
            'page_id' => 3,
            'user_id' => $user->user_id,
            'content' => 'Sara Mahmoud',
        ]);
    }



    public function deleteDiary() {
        $current_user = auth()->user();
        $pages = Diary_pages::where('user_id', $current_user->user_id);
        $pages->delete();
    }

    public function setContent(Request $request){
        if ($request->pageContent == null)
            return response()->noContent();
        else{
            $page_no = $request->page_no;
            $page_no = ceil($page_no / 2);
            $current_user = auth()->user();
            $page = Diary_pages::where([
                ['page_id', $page_no],
                ['user_id', $current_user->user_id]
            ])->first();
            if ($page === null){
                $page = new Diary_pages();
                $page->page_id = $page_no;
                $page->user_id = $current_user->user_id;
            }
            $page->content = $request->pageContent;
            $page->bookmarked = $request->bookmarked;
            $page->save();
            return response()->noContent();
        }
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
