<?php

use App\Models\Diary_pages;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
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

    /** @test */
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
            'page_id' => 3,
            'user_id' => $user->user_id,
            'content' => 'Sara',
        ]);
    }

    /** @test */
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

    /** @test */
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
            'page_id' => 3,
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
            'page_id' => 3,
            'user_id' => $user->user_id,
            'content' => 'Farah Mourad & Sara Mahmoud',
            'bookmarked' => true,
        ]);
    }

    /** @test */
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

    /** @test */
    public function can_not_delete_if_there_is_no_diary()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);
        $request = Request::create('/deleteDiary', 'POST', [
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->deleteDiary();
        })->assertStatus(204);
    }

    /** @test */
    public function get_last_even_page_for_a_user()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 1,
            'pageContent' => 'Sara Samer',
            'bookmarked' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 2,
            'pageContent' => 'Aya Basel Pancee Farah',
            'bookmarked' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $request = Request::create('/getDiary', 'GET', [
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->getLastPage();
        });

        $this->assertSame(json_encode([
            "left_content" => 'Sara Samer',
            "right_content" => 'Aya Basel Pancee Farah',
            "left_page" => 1,
            "right_page" => 2,
            "left_bookmarked" => 0,
            "right_bookmarked" => 0,
        ]), $response->getContent(), '');
    }

    /** @test */
    public function get_last_odd_page_for_a_user()
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 1,
            'pageContent' => 'Sara Samer',
            'bookmarked' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 2,
            'pageContent' => 'Aya Basel Pancee Farah',
            'bookmarked' => false,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $request = Request::create('/setContent', 'POST', [
            'page_no' => 3,
            'pageContent' => 'AyaKhamis BaselAyman PanceeWahid',
            'bookmarked' => true,
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->setContent($request);
        })->assertStatus(204);

        $request = Request::create('/getDiary', 'GET', [
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->getLastPage();
        });

        $this->assertSame(json_encode([
            "left_content" => 'AyaKhamis BaselAyman PanceeWahid',
            "right_content" => null,
            "left_page" => 3,
            "right_page" => 4,
            "left_bookmarked" => 1,
            "right_bookmarked" => null,
        ]), $response->getContent(), '');
    }

    public function getLastPage(): JsonResponse
    {
        $current_user = auth()->user();
        $last_page = Diary_pages::where('user_id', $current_user->user_id)->latest('page_id')->first();
        if ($last_page == null){
            return response()->json([
                "left_content" => null,
                "right_content" => null,
                "left_page" => null,
                "right_page" => null,
                "left_bookmarked" => null,
                "right_bookmarked" => null
            ]);
        } else {
            $page_no = $last_page->page_id;
            if($page_no % 2 == 0){
                $left_content = Diary_pages::where([
                    ['user_id', $current_user->user_id],
                    ['page_id', $page_no - 1]
                ])->first();
                echo $last_page;
                $left_page = $page_no - 1;
                $right_page = $page_no;
                $left_bookmark = $left_content->bookmarked;
                $right_bookmark = $last_page->bookmarked;
                $left_content = $left_content->content;
                $right_content = $last_page->content;
            } else {
                $left_page = $page_no;
                $right_page = $page_no + 1;
                $left_bookmark = $last_page->bookmarked;
                $right_bookmark = null;
                $left_content = $last_page->content;
                $right_content = null;
            }
            return response()->json([
                "left_content" => $left_content,
                "right_content" => $right_content,
                "left_page" => $left_page,
                "right_page" => $right_page,
                "left_bookmarked" => $left_bookmark,
                "right_bookmarked" => $right_bookmark
            ]);
        }
    }

    public function deleteDiary() {
        $current_user = auth()->user();
        $pages = Diary_pages::where('user_id', $current_user->user_id);
        echo $pages->get();

        $pages->delete();
        return response()->noContent();
    }

    public function setContent(Request $request){
        if ($request->pageContent == null)
            return response()->noContent();
        else{
            $page_no = $request->page_no;
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
