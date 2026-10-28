<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One table for all admin-editable lists (status, materials, cavities, positions).
     */
    public function up(): void
    {
        Schema::create('lookups', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);
            $table->string('name', 100);
            $table->string('color', 20)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            // ID from the matching Access lookup table, used by the import.
            $table->unsignedInteger('legacy_id')->nullable();
            $table->timestamps();

            $table->unique(['type', 'name']);
            $table->index(['type', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lookups');
    }
};
