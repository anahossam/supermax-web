<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Legacy Access data repeats (registered_on, number) (e.g. 12/09/2017 has order 1 twice), so that pair is
     * only indexed now. The unique `code` still keeps new orders apart: for them code = number + ddmmyy.
     * Access "Field1" (legacy_field1) is empty in all 263 rows and is dropped.
     */
    public function up(): void
    {
        foreach (['production_orders', 'maintenance_orders'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropUnique(['registered_on', 'number']);
                $table->index(['registered_on', 'number']);
            });
        }

        Schema::table('production_orders', function (Blueprint $table) {
            $table->dropColumn('legacy_field1');
        });
    }

    /**
     * Re-adding the unique index fails once legacy duplicates are imported.
     */
    public function down(): void
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->string('legacy_field1')->nullable()->after('notes');
        });

        foreach (['production_orders', 'maintenance_orders'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropIndex(['registered_on', 'number']);
                $table->unique(['registered_on', 'number']);
            });
        }
    }
};
