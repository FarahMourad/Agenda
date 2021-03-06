<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Note_category;
use App\Models\Task_category;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
        $user = User::create([
            'fName' => $data['fName'],
            'lName' => $data['lName'],
            'user_id' => $data['user_id'],
            'gender' => $data['gender'],
            'birthDate' => $data['birthDate'],
            'theme' => true,
            'password' => Hash::make($data['password']),
        ]);
        $shared = new Note_category();
        $shared->user_id = $user->user_id;
        $shared->category = 'Shared with me';
        $shared->save();
        $shared = new Task_category();
        $shared->user_id = $user->user_id;
        $shared->category = 'Assigned to me';
        $shared->save();
        return $user;
    }
}
