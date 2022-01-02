<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AuthenticatedUserTest extends TestCase
{

    /** @test  */
    public function a_user_can_be_added_through_the_form()
    {
        Event::fake();

        $this->actingAs(UserFactory::new()->create());

        $response = $this->post('/register', $this->data());

        $this->assertCount(9, User::all());
    }

    /** @test */
    public function only_logged_in_users_can_see_their_home()
    {
        $response = $this->get('/home') -> assertRedirect('/login');
    }

    /** @test */
    public function only_logged_in_users_can_see_their_setting()
    {
        $response = $this->get('/setting') -> assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_see_their_home_page()
    {
        $this->actingAs(UserFactory::new()->create());

        $response = $this->get('/home') -> assertOk();
    }

    private function data(): array
    {
        return [
            'fName' => 'test',
            'lName' => 'user',
            'user_id' => 'test_user',
            'gender' => 'male',
            'birthDate' => '2000-10-06',
            'theme' => false,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ];
    }
}
