<?php

declare(strict_types=1);

namespace App;

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

            if (KnownItemName::AGED_BRIE !== $item->name && KnownItemName::BACKSTAGE_PASSES !== $item->name) {
                $this->decreaseQuality($item);
            } elseif ($item->quality < 50) {
                $this->doIncreaseQuality($item);
                if (KnownItemName::BACKSTAGE_PASSES === $item->name && $this->canIncreaseQuality($item)) {
                    if ($item->sell_in < 11) {
                        $this->doIncreaseQuality($item);
                    }
                    if ($item->sell_in < 6) {
                        $this->doIncreaseQuality($item);
                    }
                }
            }

            $this->decreaseSellIn($item);

            if ($this->isExpired($item)) {
                if (KnownItemName::AGED_BRIE === $item->name) {
                    $this->increaseQuality($item);
                } elseif (KnownItemName::BACKSTAGE_PASSES === $item->name) {
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
}
