<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_register_and_is_redirected_home(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Иван Иванов',
            'email' => 'ivan@example.com',
            'phone' => '+7 900 123-45-67',
            'password' => 'Password1',
            'password_confirmation' => 'Password1',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'ivan@example.com',
            'role' => 'visitor',
        ]);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'visitor@example.com',
            'password' => Hash::make('Password1'),
            'role' => 'visitor',
        ]);

        $this->post(route('login.submit'), [
            'email' => 'visitor@example.com',
            'password' => 'Password1',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_invalid_login_returns_validation_error(): void
    {
        User::factory()->create([
            'email' => 'visitor@example.com',
            'password' => Hash::make('Password1'),
        ]);

        $this->from(route('login'))->post(route('login.submit'), [
            'email' => 'visitor@example.com',
            'password' => 'WrongPassword1',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
