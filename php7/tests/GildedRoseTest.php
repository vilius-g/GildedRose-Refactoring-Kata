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

    public function getItemData(): array
    {
        return [
           'Generic item, will sell in one day' => [new Item('Generic Item', 1, 3), 0, 2],
           'Generic item, will sell today' => [new Item('Generic Item', 0, 2), -1, 0],
           'Generic item, will sell yesterday' => [new Item('Generic Item', -1, 0), -2, 0],
        ];
    }

    /**
     * Test item update using provided test data
     *
     * @param Item $item
     * @param int $expectedSellIn
     * @param int $expectedQuality
     * @dataProvider getItemData
     */
    public function testItem(Item $item, int $expectedSellIn, int $expectedQuality): void
    {
        $gildedRose = $this->createGildedRoseInstance($item);

        $gildedRose->updateQuality();
        $this->assertEquals($expectedSellIn, $item->sell_in);
        $this->assertEquals($expectedQuality, $item->quality);
    }
}
