<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

/**
 * Makes adjustments to item properties.
 */
final class ItemManipulator
{
    private const QUALITY_MAX = 50;
    private const QUALITY_MIN = 0;

    /**
     * Decrease sell_in value for item.
     */
    public function decreaseSellIn(Item $item): void
    {
        --$item->sell_in;
    }

    /**
     * Decrease item quality.
     */
    public function decreaseQuality(Item $item, int $amount = 1): void
    {
        $this->validateAmountPositive($amount);
        $item->quality = max($item->quality - $amount, self::QUALITY_MIN);
    }

    /**
     * Reset quality to 0.
     */
    public function resetQuality(Item $item): int
    {
        return $item->quality -= $item->quality;
    }

    /**
     * Increase item quality.
     */
    public function increaseQuality(Item $item, int $amount = 1): void
    {
        $this->validateAmountPositive($amount);
        $item->quality = min($item->quality + $amount, self::QUALITY_MAX);
    }

    /**
     * Ensure that given value is positive.
     */
    private function validateAmountPositive(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('$amount must be positive: '.$amount);
        }
    }
}
