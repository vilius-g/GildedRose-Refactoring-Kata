<?php

namespace App\Tests;

use App\GildedRose;
use App\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    /**
     * Create GildedRose instance with a single item.
     *
     * @param Item $item
     * @return GildedRose
     */
    private function createGildedRoseInstance(Item $item): GildedRose
    {
        return new GildedRose([$item]);
    }

    public function testGenericItem(): void
    {
        $item = new Item('Generic Item', 0, 0);
        $gildedRose = $this->createGildedRoseInstance($item);
        $gildedRose->updateQuality();
        $this->assertEquals('Generic Item', $item->name);
        $this->assertEquals(-1, $item->sell_in);
        $this->assertEquals(0, $item->quality);
    }
}
