<?php

declare(strict_types=1);

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
            'Aged Brie, unexpired' => [new Item('Aged Brie', 1, 1), 0, 2],
            'Aged Brie, expired' => [new Item('Aged Brie', -1, 1), -2, 3],
            'Aged Brie, max quality' => [new Item('Aged Brie', 1, 50), 0, 50],
            'Sulfuras, unexpired' => [new Item('Sulfuras, Hand of Ragnaros', 1, 80), 1, 80],
            'Sulfuras, expired' => [new Item('Sulfuras, Hand of Ragnaros', -1, 80), -1, 80],
            'Backstage passes, early' => [new Item('Backstage passes to a TAFKAL80ETC concert', 11, 1), 10, 2,],
            'Backstage passes, 10 days or less' => [new Item('Backstage passes to a TAFKAL80ETC concert', 10, 1), 9, 3],
            'Backstage passes, 5 days or less' => [new Item('Backstage passes to a TAFKAL80ETC concert', 5, 1), 4, 4],
            'Backstage passes, expired' => [new Item('Backstage passes to a TAFKAL80ETC concert', 0, 1), -1, 0],
            'Backstage passes, max quality' => [new Item('Backstage passes to a TAFKAL80ETC concert', 1, 50), 0, 50],
            'Backstage passes, almost max quality' => [new Item('Backstage passes to a TAFKAL80ETC concert', 1, 49), 0, 50],
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
        $this->assertEquals($expectedSellIn, $item->sell_in, 'SellIn should be updated correctly.');
        $this->assertEquals($expectedQuality, $item->quality, 'Quality should be updated correctly.');
    }
}
