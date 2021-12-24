<?php

use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistersUsersTest extends TestCase
{
    use RegistersUsers;
    use DatabaseTransactions;

    /** @test */
    public function it_can_register_a_user()
    {
        $request = Request::create('/register', 'POST', [
            'fName' => 'Taylor',
            'lName' => 'Otwell',
            'user_id' => 'taylorOtw',
            'gender' => 'Male',
            'birthDate' => '1997-05-27',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->register($request);
        })->assertCreated();

        $this->assertDatabaseHas('users', [
            'fName' => 'Taylor',
            'user_id' => 'taylorOtw',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'fName' => ['required', 'string', 'max:20'],
            'lName' => ['required', 'string', 'max:20'],
            'user_id' => ['required', 'string', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data): User
    {
        $user = (new User())->forceFill([
            'fName' => $data['fName'],
            'lName' => $data['lName'],
            'user_id' => $data['user_id'],
            'gender' => $data['gender'],
            'birthDate' => $data['birthDate'],
            'theme' => true,
            'password' => Hash::make($data['password']),
        ]);

        $user->save();

        return $user;
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
