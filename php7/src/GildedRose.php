<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use function in_array;
use function max;
use function min;

final class GildedRose
{
    private const QUALITY_MAX = 50;
    private const QUALITY_MIN = 0;
    /** @var Item[] */
    private array $items;

    /**
     * GildedRose constructor.
     * @param Item[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Adjust item quality after each day.
     */
    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($this->isLegendary($item)) {
                continue;
            }

            if ($this->qualityIncreasesWithAge($item)) {
                $this->increaseQuality($item, $this->getQualityIncrement($item));
            } else {
                $this->decreaseQuality($item);
            }

            $this->decreaseSellIn($item);

            if ($this->isExpired($item)) {
                if ($this->qualityIncreasesAfterExpiration($item)) {
                    $this->increaseQuality($item);
                } elseif ($this->qualityResetsAfterExpiration($item)) {
                    $this->resetQuality($item);
                } else {
                    $this->decreaseQuality($item);
                }
            }
        }
    }

    /**
     * Decrease sell_in value for item.
     *
     * @param Item $item
     */
    private function decreaseSellIn(Item $item): void
    {
        --$item->sell_in;
    }

    /**
     * Return whether item has passed its sell_in date.
     *
     * @param Item $item
     * @return bool
     */
    private function isExpired(Item $item): bool
    {
        return $item->sell_in < 0;
    }

    /**
     * Is item legendary and cannot be altered.
     *
     * @param Item $item
     * @return bool
     */
    private function isLegendary(Item $item): bool
    {
        return KnownItemName::SULFURAS === $item->name;
    }

    /**
     * Decrease quality by one but only if allowed for this item.
     *
     * @param Item $item
     */
    private function decreaseQuality(Item $item): void
    {
        $item->quality = max($item->quality - 1, self::QUALITY_MIN);
    }

    /**
     * Reset quality to 0.
     *
     * @param Item $item
     * @return int
     */
    private function resetQuality(Item $item): int
    {
        return $item->quality -= $item->quality;
    }

    /**
     * Increase quality but only if allowed.
     *
     * @param Item $item
     * @param int $amount
     */
    private function increaseQuality(Item $item, int $amount = 1): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('$amount must be positive: '.$amount);
        }

        $item->quality = min($item->quality + $amount, self::QUALITY_MAX);
    }

    /**
     * Does item quality increase with age.
     *
     * @param Item $item
     * @return bool
     */
    private function qualityIncreasesWithAge(Item $item): bool
    {
        return in_array($item->name, [KnownItemName::AGED_BRIE, KnownItemName::BACKSTAGE_PASSES], true);
    }

    /**
     * Does quality still increase after sell by date.
     *
     * @param Item $item
     * @return bool
     */
    private function qualityIncreasesAfterExpiration(Item $item): bool
    {
        return KnownItemName::AGED_BRIE === $item->name;
    }

    /**
     * Does quality reset to zero after sell by date.
     *
     * @param Item $item
     * @return bool
     */
    private function qualityResetsAfterExpiration(Item $item): bool
    {
        return KnownItemName::BACKSTAGE_PASSES === $item->name;
    }

    /**
     * Return how much the quality should be increased.
     *
     * @param Item $item
     * @return int
     */
    private function getQualityIncrement(Item $item): int
    {
        if (KnownItemName::BACKSTAGE_PASSES === $item->name) {
            if ($item->sell_in <= 5) {
                return 3;
            }
            if ($item->sell_in <= 10) {
                return 2;
            }
        }

        return 1;
    }
}
