<?php


use Database\Factories\UserFactory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthenticatesUserTest extends TestCase
{
    use AuthenticatesUsers;
    use DatabaseTransactions;

    /** @test
     */
    public function it_can_authenticate_a_user()
    {
        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'user_id' => $user->user_id,
            'password' => 'password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertStatus(204);
    }

    /** @test
     */
    public function it_cant_authenticate_a_user_with_invalid_password()
    {
        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'user_id' => $user->user_id,
            'password' => 'invalid-password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertUnprocessable();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
        $this->assertSame([
            'user_id' => [
                'These credentials do not match our records.',
            ],
        ], $response->exception->errors());
    }

    /** @test
     */
    public function it_cant_authenticate_unknown_credential()
    {
        $request = Request::create('/login', 'POST', [
            'user_id' => 'taylorOtw',
            'password' => 'password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertUnprocessable();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
        $this->assertSame([
            'user_id' => [
                'These credentials do not match our records.',
            ],
        ], $response->exception->errors());
    }

    /**
     * Handle Request using the following pipeline.
     *
     * @param Request $request
     * @param  callable  $callback
     * @return TestResponse
     */
    protected function handleRequestUsing(Request $request, callable $callback)
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
