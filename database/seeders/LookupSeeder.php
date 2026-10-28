<?php

namespace Database\Seeders;

use App\Models\Lookup;
use Illuminate\Database\Seeder;

/**
 * The lookup lists from the Access database, in their Access ID order (legacy_id = position, 1-based).
 * Safe to run again: existing rows keep their admin edits and only get a missing legacy_id filled in.
 */
class LookupSeeder extends Seeder
{
    public function run(): void
    {
        $lists = [
            Lookup::STATUS => [
                ['name' => 'تحت التشغيل', 'color' => 'blue', 'is_default' => true],
                ['name' => 'خالص', 'color' => 'green'],
                ['name' => 'خروج', 'color' => 'purple'],
                ['name' => 'معلق', 'color' => 'orange'],
                ['name' => 'ملغي', 'color' => 'red'],
                ['name' => 'مرتجع', 'color' => 'gray'],
            ],
            Lookup::MOULD_MATERIAL => [['name' => 'صلب'], ['name' => 'حديد'], ['name' => 'المونيوم'], ['name' => 'نحاس']],
            Lookup::SAMPLE_MATERIAL => [['name' => 'PE'], ['name' => 'PP'], ['name' => 'PET'], ['name' => 'PVC']],
            Lookup::CAVITY => [['name' => 'سنجل'], ['name' => 'دبل'], ['name' => 'تريبل'], ['name' => '4 عين']],
            Lookup::POSITION => [['name' => 'رقبة'], ['name' => 'بدن'], ['name' => 'كعب']],
        ];

        foreach ($lists as $type => $rows) {
            foreach ($rows as $i => $row) {
                $lookup = Lookup::firstOrCreate(
                    ['type' => $type, 'name' => $row['name']],
                    [...$row, 'sort_order' => $i + 1, 'legacy_id' => $i + 1],
                );
                if ($lookup->legacy_id === null) {
                    $lookup->update(['legacy_id' => $i + 1]);
                }
            }
        }
    }
}
