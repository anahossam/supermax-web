<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            // Business key: number + registration date as ddmmyy (e.g. 2281026).
            $table->string('code', 12)->unique();
            // Daily sequence, restarts at 1 every registration day.
            $table->unsignedSmallInteger('number');
            $table->date('registered_on');
            $table->date('run_on')->nullable();

            $table->foreignId('customer_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('company')->nullable();
            $table->foreignId('status_id')->constrained('lookups')->restrictOnDelete();

            $table->string('capacity', 50)->nullable();
            $table->decimal('center_size', 8, 2)->nullable();
            $table->string('machine_type', 100)->nullable();
            $table->unsignedSmallInteger('moulds_count')->nullable();
            $table->foreignId('mould_material_id')->nullable()->constrained('lookups')->restrictOnDelete();
            $table->foreignId('sample_material_id')->nullable()->constrained('lookups')->restrictOnDelete();
            $table->foreignId('cavity_id')->nullable()->constrained('lookups')->restrictOnDelete();

            $table->boolean('has_text')->default(false);
            $table->string('text_content')->nullable();
            $table->foreignId('text_position_id')->nullable()->constrained('lookups')->restrictOnDelete();
            $table->boolean('has_inserts')->default(false);
            $table->string('insert_text')->nullable();
            $table->foreignId('insert_position_id')->nullable()->constrained('lookups')->restrictOnDelete();

            $table->text('notes')->nullable();
            // Access "Field1": purpose not confirmed yet, kept as-is until mapped.
            $table->string('legacy_field1')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Guards daily numbering against two users saving at the same moment.
            $table->unique(['registered_on', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
