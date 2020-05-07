<?php

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
            if (KnownItemName::AGED_BRIE != $item->name and KnownItemName::BACKSTAGE_PASSES != $item->name) {
                if ($item->quality > 0) {
                    if (KnownItemName::SULFURAS != $item->name) {
                        $item->quality = $item->quality - 1;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                    if (KnownItemName::BACKSTAGE_PASSES == $item->name) {
                        if ($item->sell_in < 11) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                        if ($item->sell_in < 6) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                    }
                }
            }

            if (KnownItemName::SULFURAS != $item->name) {
                $item->sell_in = $item->sell_in - 1;
            }

            if ($item->sell_in < 0) {
                if (KnownItemName::AGED_BRIE != $item->name) {
                    if (KnownItemName::BACKSTAGE_PASSES != $item->name) {
                        if ($item->quality > 0) {
                            if (KnownItemName::SULFURAS != $item->name) {
                                $item->quality = $item->quality - 1;
                            }
                        }
                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                }
            }
        }
    }
}
