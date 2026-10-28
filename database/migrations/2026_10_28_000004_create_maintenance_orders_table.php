<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_orders', function (Blueprint $table) {
            $table->id();
            // Business key: number + registration date as ddmmyy; numbering is separate from production orders.
            $table->string('code', 12)->unique();
            $table->unsignedSmallInteger('number');
            $table->date('registered_on');
            $table->date('run_on')->nullable();

            $table->foreignId('customer_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('company')->nullable();
            $table->foreignId('status_id')->constrained('lookups')->restrictOnDelete();

            $table->text('mould_description')->nullable();
            $table->text('required_maintenance')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['registered_on', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_orders');
    }
};
