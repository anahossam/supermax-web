<?php

namespace Tests\Feature;

use App\Models\Lookup;
use App\Models\MaintenanceOrder;
use App\Models\ProductionOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OrderNumberingTest extends TestCase
{
    use RefreshDatabase;

    private int $statusId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\LookupSeeder::class);
        $this->statusId = Lookup::where('type', Lookup::STATUS)->value('id');
    }

    public function test_second_order_of_the_same_day_gets_the_next_number(): void
    {
        $first = ProductionOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $this->statusId]);
        $second = ProductionOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $this->statusId]);
        $nextDay = ProductionOrder::createNumbered(['registered_on' => '2026-10-29', 'status_id' => $this->statusId]);

        $this->assertSame([1, '1281026'], [$first->number, $first->code]);
        $this->assertSame([2, '2281026'], [$second->number, $second->code]);
        $this->assertSame([1, '1291026'], [$nextDay->number, $nextDay->code]);
    }

    public function test_maintenance_orders_number_separately(): void
    {
        ProductionOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $this->statusId]);
        $order = MaintenanceOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $this->statusId]);

        $this->assertSame([1, '1281026'], [$order->number, $order->code]);
    }

    public function test_deleted_orders_still_count(): void
    {
        ProductionOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $this->statusId])->delete();
        $order = ProductionOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $this->statusId]);

        $this->assertSame(2, $order->number);
    }

    public function test_code_collision_retries_with_the_next_number(): void
    {
        $this->legacy('1281026', 1, '2026-10-28');
        $this->legacy('2281026', 5, '2017-01-01'); // hand-typed legacy code that happens to match

        $order = ProductionOrder::createNumbered(['registered_on' => '2026-10-28', 'status_id' => $this->statusId]);

        $this->assertSame([3, '3281026'], [$order->number, $order->code]);
    }

    public function test_legacy_rows_may_repeat_date_and_number(): void
    {
        // Access PRODCTIOB PLANE: 12/09/2017 has order number 1 twice.
        $this->legacy('1120917', 1, '2017-09-12');
        $this->legacy('111022017', 1, '2017-09-12');

        $this->assertSame(2, ProductionOrder::whereDate('registered_on', '2017-09-12')->where('number', 1)->count());
        $this->assertFalse(Schema::hasColumn('production_orders', 'legacy_field1'));
    }

    private function legacy(string $code, int $number, string $date): void
    {
        $order = new ProductionOrder(['registered_on' => $date, 'status_id' => $this->statusId]);
        $order->forceFill(['code' => $code, 'number' => $number])->save();
    }
}
