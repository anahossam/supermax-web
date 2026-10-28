<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public static function sharedPages(): array
    {
        return [['/'], ['/production-orders'], ['/maintenance-orders'], ['/customers']];
    }

    public static function adminPages(): array
    {
        return [['/lists'], ['/users'], ['/audit-log']];
    }

    #[DataProvider('sharedPages')]
    public function test_every_role_can_open_shared_pages(string $url): void
    {
        foreach ([User::factory()->admin(), User::factory(), User::factory()->viewer()] as $factory) {
            $this->actingAs($factory->create())->get($url)->assertOk();
        }
    }

    #[DataProvider('adminPages')]
    public function test_only_admin_can_open_admin_pages(string $url): void
    {
        $this->actingAs(User::factory()->admin()->create())->get($url)->assertOk();
        $this->actingAs(User::factory()->create())->get($url)->assertForbidden();
        $this->actingAs(User::factory()->viewer()->create())->get($url)->assertForbidden();
    }

    public function test_gates_match_roles(): void
    {
        $admin = User::factory()->admin()->create();
        $editor = User::factory()->create();
        $viewer = User::factory()->viewer()->create();

        $this->assertTrue($admin->can('admin'));
        $this->assertTrue($admin->can('edit'));
        $this->assertFalse($editor->can('admin'));
        $this->assertTrue($editor->can('edit'));
        $this->assertFalse($viewer->can('admin'));
        $this->assertFalse($viewer->can('edit'));
    }

    public function test_settings_menu_is_only_shown_to_admin(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/')->assertSee('سجل التعديلات');
        $this->actingAs(User::factory()->create())->get('/')->assertDontSee('سجل التعديلات');
    }
}
