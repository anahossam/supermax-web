<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/production-orders')->assertRedirect('/login');
    }

    public function test_login_page_renders_in_arabic_rtl(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('تسجيل الدخول')
            ->assertSee('Hossam Ibrahim');
    }

    public function test_user_can_log_in_with_username(): void
    {
        $user = User::factory()->create(['username' => 'ahmed']);

        $this->post('/login', ['username' => 'ahmed', 'password' => 'password'])
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_is_refused(): void
    {
        User::factory()->create(['username' => 'ahmed']);

        $this->from('/login')->post('/login', ['username' => 'ahmed', 'password' => 'wrong'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        User::factory()->inactive()->create(['username' => 'ahmed']);

        $this->post('/login', ['username' => 'ahmed', 'password' => 'password'])
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_deactivated_user_is_logged_out_on_next_request(): void
    {
        $user = User::factory()->inactive()->create();

        $this->actingAs($user)->get('/')->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_user_can_log_out(): void
    {
        $this->actingAs(User::factory()->create())->post('/logout')->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_there_is_no_public_registration(): void
    {
        $this->get('/register')->assertNotFound();
    }
}
