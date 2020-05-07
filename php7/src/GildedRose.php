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
            if (KnownItemName::AGED_BRIE !== $item->name && KnownItemName::BACKSTAGE_PASSES !== $item->name) {
                if ($item->quality > 0 && KnownItemName::SULFURAS !== $item->name) {
                    --$item->quality;
                }
            } elseif ($item->quality < 50) {
                ++$item->quality;
                if (KnownItemName::BACKSTAGE_PASSES === $item->name && $item->quality < 50) {
                    if ($item->sell_in < 11) {
                        ++$item->quality;
                    }
                    if ($item->sell_in < 6) {
                        ++$item->quality;
                    }
                }
            }

            $this->decreaseSellIn($item);

            if ($this->isExpired($item)) {
                if (KnownItemName::AGED_BRIE !== $item->name) {
                    if (KnownItemName::BACKSTAGE_PASSES !== $item->name) {
                        if ($item->quality > 0 && KnownItemName::SULFURAS !== $item->name) {
                            --$item->quality;
                        }
                    } else {
                        $item->quality -= $item->quality;
                    }
                } elseif ($item->quality < 50) {
                    ++$item->quality;
                }
            }
        }
    }

    /**
     * Return whether sell_in value be decreased for this item.
     *
     * @param Item $item
     * @return bool
     */
    private function canDecreaseSellIn(Item $item): bool
    {
        return KnownItemName::SULFURAS !== $item->name;
    }

    /**
     * Decrease sell_in value for item.
     *
     * @param Item $item
     */
    private function decreaseSellIn(Item $item): void
    {
        if ($this->canDecreaseSellIn($item)) {
            --$item->sell_in;
        }
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
}
