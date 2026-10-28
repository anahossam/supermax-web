<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_resets_the_password_from_a_prompt(): void
    {
        $user = User::factory()->create(['username' => 'admin']);

        $this->artisan('user:reset-password admin')
            ->expectsQuestion('New password (at least 8 characters)', 'new-secret-123')
            ->expectsQuestion('Repeat the new password', 'new-secret-123')
            ->assertSuccessful();

        $this->assertTrue(Hash::check('new-secret-123', $user->fresh()->password));
    }

    public function test_refuses_mismatch_short_password_and_unknown_user(): void
    {
        $user = User::factory()->create(['username' => 'admin']);
        $before = $user->password;

        $this->artisan('user:reset-password admin')
            ->expectsQuestion('New password (at least 8 characters)', 'new-secret-123')
            ->expectsQuestion('Repeat the new password', 'other-secret-123')
            ->assertFailed();
        $this->artisan('user:reset-password admin')
            ->expectsQuestion('New password (at least 8 characters)', 'short')
            ->assertFailed();
        $this->artisan('user:reset-password nobody')->assertFailed();

        $this->assertSame($before, $user->fresh()->password);
    }
}
