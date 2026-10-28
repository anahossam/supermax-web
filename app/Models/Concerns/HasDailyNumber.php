<?php

namespace App\Models\Concerns;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Daily order numbering: `number` restarts at 1 every registration day and `code` = number + ddmmyy
 * (order 2 of 28/10/2026 → "2281026"). Soft-deleted orders count, so a number is never reused.
 */
trait HasDailyNumber
{
    private const NUMBER_ATTEMPTS = 5;

    /**
     * Create an order with the next number of its registration day. If the code is already taken
     * (another user saving at the same moment, or a hand-typed legacy code), retry with the next number.
     */
    public static function createNumbered(array $attributes): static
    {
        return DB::transaction(function () use ($attributes) {
            $day = Carbon::parse($attributes['registered_on'])->startOfDay();
            $number = (int) static::withTrashed()->whereDate('registered_on', $day)->max('number') + 1;

            for ($attempt = 1; ; $attempt++, $number++) {
                try {
                    // Savepoint per attempt, so a failed insert does not abort the outer transaction.
                    return DB::transaction(function () use ($attributes, $day, $number) {
                        $order = new static($attributes);
                        $order->registered_on = $day;
                        $order->number = $number;
                        $order->code = static::codeFor($number, $day);
                        $order->save();

                        return $order;
                    });
                } catch (UniqueConstraintViolationException $e) {
                    if ($attempt >= self::NUMBER_ATTEMPTS) {
                        throw $e;
                    }
                }
            }
        });
    }

    public static function codeFor(int $number, Carbon $day): string
    {
        return $number.$day->format('dmy');
    }
}
