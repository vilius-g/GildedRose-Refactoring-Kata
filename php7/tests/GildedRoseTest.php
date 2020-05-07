<?php

namespace App\Tests;

use App\GildedRose;
use App\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertEquals('foo', $items[0]->name);
        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }
}
