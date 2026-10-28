<?php

namespace App\Models;

use App\Models\Concerns\HasDailyNumber;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'registered_on', 'run_on', 'customer_id', 'company', 'status_id',
    'capacity', 'center_size', 'machine_type', 'moulds_count',
    'mould_material_id', 'sample_material_id', 'cavity_id',
    'has_text', 'text_content', 'text_position_id',
    'has_inserts', 'insert_text', 'insert_position_id', 'notes',
])]
class ProductionOrder extends Model
{
    use HasDailyNumber, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'registered_on' => 'date',
            'run_on' => 'date',
            'center_size' => 'decimal:2',
            'has_text' => 'boolean',
            'has_inserts' => 'boolean',
        ];
    }

    /**
     * Orders whose status means "under production" (تحت التشغيل), whatever status is the default.
     */
    public function scopeInProduction(Builder $query): void
    {
        $query->whereHas('status', fn (Builder $q) => $q->where('legacy_id', Lookup::STATUS_IN_PRODUCTION));
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Lookup::class, 'status_id');
    }

    public function mouldMaterial(): BelongsTo
    {
        return $this->belongsTo(Lookup::class, 'mould_material_id');
    }

    public function sampleMaterial(): BelongsTo
    {
        return $this->belongsTo(Lookup::class, 'sample_material_id');
    }

    public function cavity(): BelongsTo
    {
        return $this->belongsTo(Lookup::class, 'cavity_id');
    }

    public function textPosition(): BelongsTo
    {
        return $this->belongsTo(Lookup::class, 'text_position_id');
    }

    public function insertPosition(): BelongsTo
    {
        return $this->belongsTo(Lookup::class, 'insert_position_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
