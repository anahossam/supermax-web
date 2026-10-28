<?php

namespace App\Models;

use App\Models\Concerns\HasDailyNumber;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'registered_on', 'run_on', 'customer_id', 'company', 'status_id',
    'mould_description', 'required_maintenance', 'notes',
])]
class MaintenanceOrder extends Model
{
    use HasDailyNumber, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'registered_on' => 'date',
            'run_on' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Lookup::class, 'status_id');
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
