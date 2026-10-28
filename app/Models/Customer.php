<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'company'])]
class Customer extends Model
{
    use HasFactory;

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }

    public function maintenanceOrders(): HasMany
    {
        return $this->hasMany(MaintenanceOrder::class);
    }

    /**
     * A customer used by any order (including soft-deleted ones) cannot be deleted.
     */
    public function isInUse(): bool
    {
        return $this->productionOrders()->withTrashed()->exists()
            || $this->maintenanceOrders()->withTrashed()->exists();
    }
}
