<?php

declare(strict_types=1);

namespace App;

use function in_array;

final class GildedRose
{
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

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($this->isLegendary($item)) {
                continue;
            }

            if ($this->qualityIncreasesWithAge($item)) {
                $this->increaseQuality($item);
                if (KnownItemName::BACKSTAGE_PASSES === $item->name) {
                    if ($item->sell_in < 11) {
                        $this->increaseQuality($item);
                    }
                    if ($item->sell_in < 6) {
                        $this->increaseQuality($item);
                    }
                }
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
     * Check if quality can be decreased for this item.
     *
     * @param Item $item
     * @return bool
     */
    private function canDecreaseQuality(Item $item): bool
    {
        return $item->quality > 0;
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
        if ($this->canDecreaseQuality($item)) {
            --$item->quality;
        }
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
     * Return whether quality can be increased for this item.
     *
     * @param Item $item
     * @return bool
     */
    private function canIncreaseQuality(Item $item): bool
    {
        return $item->quality < 50;
    }

    /**
     * Increase quality but only if allowed.
     *
     * @param Item $item
     */
    private function increaseQuality(Item $item): void
    {
        if ($this->canIncreaseQuality($item)) {
            $this->doIncreaseQuality($item);
        }
    }

    /**
     * Increase quality by one without any checks.
     *
     * @param Item $item
     * @return int
     */
    private function doIncreaseQuality(Item $item): int
    {
        return ++$item->quality;
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
}
