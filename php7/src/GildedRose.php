<?php

namespace App;

final class GildedRose
{
    private $items = [];

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function updateQuality()
    {
        foreach ($this->items as $item) {
            if ('Aged Brie' != $item->name and 'Backstage passes to a TAFKAL80ETC concert' != $item->name) {
                if ($item->quality > 0) {
                    if ('Sulfuras, Hand of Ragnaros' != $item->name) {
                        $item->quality = $item->quality - 1;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                    if ('Backstage passes to a TAFKAL80ETC concert' == $item->name) {
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

            if ('Sulfuras, Hand of Ragnaros' != $item->name) {
                $item->sell_in = $item->sell_in - 1;
            }

            if ($item->sell_in < 0) {
                if ('Aged Brie' != $item->name) {
                    if ('Backstage passes to a TAFKAL80ETC concert' != $item->name) {
                        if ($item->quality > 0) {
                            if ('Sulfuras, Hand of Ragnaros' != $item->name) {
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
