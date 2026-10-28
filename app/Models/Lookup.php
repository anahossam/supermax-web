<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['type', 'name', 'color', 'is_default', 'is_active', 'sort_order', 'legacy_id'])]
class Lookup extends Model
{
    public const STATUS = 'status';
    public const MOULD_MATERIAL = 'mould_material';
    public const SAMPLE_MATERIAL = 'sample_material';
    public const CAVITY = 'cavity';
    public const POSITION = 'position';

    /**
     * Access ID (legacy_id) of the status "تحت التشغيل". Unlike the name or the default flag,
     * it cannot be changed from the admin screens.
     */
    public const STATUS_IN_PRODUCTION = 1;

    /**
     * Admin-facing names of each list, in display order.
     */
    public const TYPES = [
        self::STATUS => 'حالة التشغيل',
        self::MOULD_MATERIAL => 'خامة القالب',
        self::SAMPLE_MATERIAL => 'خامة العينة',
        self::CAVITY => 'عدد العيون',
        self::POSITION => 'مكان الكتابة / الانسرت',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeOfType(Builder $query, string $type): void
    {
        $query->where('type', $type)->orderBy('sort_order')->orderBy('name');
    }

    public static function defaultStatus(): ?self
    {
        return static::where('type', self::STATUS)->where('is_default', true)->first();
    }
}
