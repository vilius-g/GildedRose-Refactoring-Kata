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
                if ($item->quality > 0) {
                    if (KnownItemName::SULFURAS !== $item->name) {
                        --$item->quality;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    ++$item->quality;
                    if (KnownItemName::BACKSTAGE_PASSES === $item->name) {
                        if ($item->sell_in < 11) {
                            if ($item->quality < 50) {
                                ++$item->quality;
                            }
                        }
                        if ($item->sell_in < 6) {
                            if ($item->quality < 50) {
                                ++$item->quality;
                            }
                        }
                    }
                }
            }

            if (KnownItemName::SULFURAS !== $item->name) {
                --$item->sell_in;
            }

            if ($item->sell_in < 0) {
                if (KnownItemName::AGED_BRIE !== $item->name) {
                    if (KnownItemName::BACKSTAGE_PASSES !== $item->name) {
                        if ($item->quality > 0) {
                            if (KnownItemName::SULFURAS !== $item->name) {
                                --$item->quality;
                            }
                        }
                    } else {
                        $item->quality -= $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        ++$item->quality;
                    }
                }
            }
        }
    }
}
