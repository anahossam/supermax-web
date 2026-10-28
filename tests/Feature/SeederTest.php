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

    public function test_lookups_get_access_ids_also_when_they_already_exist(): void
    {
        // A row created before legacy_id was seeded, with an admin's own colour.
        Lookup::create(['type' => Lookup::CAVITY, 'name' => 'تريبل', 'color' => 'teal', 'sort_order' => 9]);

        $this->seed(\Database\Seeders\LookupSeeder::class);
        $this->seed(\Database\Seeders\LookupSeeder::class);

        $ids = fn (string $type) => Lookup::where('type', $type)->orderBy('legacy_id')->pluck('name', 'legacy_id')->all();
        $this->assertSame([1 => 'تحت التشغيل', 2 => 'خالص', 3 => 'خروج', 4 => 'معلق', 5 => 'ملغي', 6 => 'مرتجع'], $ids(Lookup::STATUS));
        $this->assertSame([1 => 'صلب', 2 => 'حديد', 3 => 'المونيوم', 4 => 'نحاس'], $ids(Lookup::MOULD_MATERIAL));
        $this->assertSame([1 => 'PE', 2 => 'PP', 3 => 'PET', 4 => 'PVC'], $ids(Lookup::SAMPLE_MATERIAL));
        $this->assertSame([1 => 'سنجل', 2 => 'دبل', 3 => 'تريبل', 4 => '4 عين'], $ids(Lookup::CAVITY));
        $this->assertSame([1 => 'رقبة', 2 => 'بدن', 3 => 'كعب'], $ids(Lookup::POSITION));

        $triple = Lookup::where('type', Lookup::CAVITY)->where('name', 'تريبل')->first();
        $this->assertSame(['teal', 9], [$triple->color, $triple->sort_order]); // admin edits kept
    }
}
