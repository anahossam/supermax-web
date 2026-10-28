<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Lookup;
use App\Models\ProductionOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_in_production_card_ignores_which_status_is_the_default(): void
    {
        $this->seed(\Database\Seeders\LookupSeeder::class);
        $inProduction = Lookup::where('type', Lookup::STATUS)->where('name', 'تحت التشغيل')->first();
        $done = Lookup::where('type', Lookup::STATUS)->where('name', 'خالص')->first();

        ProductionOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $inProduction->id]);
        ProductionOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $inProduction->id]);
        ProductionOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $done->id]);

        // An admin makes another status the default and renames the original one.
        $inProduction->update(['is_default' => false, 'name' => 'قيد التشغيل']);
        $done->update(['is_default' => true]);

        $this->assertSame(2, ProductionOrder::inProduction()->count());

        $user = User::factory()->create(['role' => Role::Viewer]);
        $this->actingAs($user)->get('/')->assertOk()->assertSeeInOrder(['تحت التشغيل الآن', '2']);
    }
}
