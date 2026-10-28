<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Lookup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeds_lookup_lists_and_one_admin_and_can_run_twice(): void
    {
        $this->seed();
        $this->seed();

        $this->assertSame(6, Lookup::where('type', Lookup::STATUS)->count());
        $this->assertSame(4, Lookup::where('type', Lookup::MOULD_MATERIAL)->count());
        $this->assertSame(4, Lookup::where('type', Lookup::SAMPLE_MATERIAL)->count());
        $this->assertSame(4, Lookup::where('type', Lookup::CAVITY)->count());
        $this->assertSame(3, Lookup::where('type', Lookup::POSITION)->count());
        $this->assertSame('تحت التشغيل', Lookup::defaultStatus()->name);

        $this->assertSame(1, User::where('role', Role::Admin)->count());
    }
}
