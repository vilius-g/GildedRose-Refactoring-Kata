<?php

declare(strict_types=1);

namespace App\Tests;

use App\Item;
use App\ItemMatcher;
use App\KnownItemName;
use PHPUnit\Framework\TestCase;

class ItemMatcherTest extends TestCase
{
    private function createInstance(): ItemMatcher
    {
        return new ItemMatcher();
    }

    public function getMatchesData(): array
    {
        return [
            'simple match' => [new Item('Aged Brie', 1, 1), [KnownItemName::AGED_BRIE]],
            'simple mismatch' => [new Item('Aged Brie 1', 1, 1), [KnownItemName::AGED_BRIE], false],
            'multiple match' => [
                new Item('Aged Brie 1', 1, 1),
                [KnownItemName::AGED_BRIE, KnownItemName::BACKSTAGE_PASSES],
                false,
            ],
            'beginning match' => [new Item('Conjured Mana Cake', 1, 1), [KnownItemName::CONJURED]],
        ];
    }

    /**
     * @dataProvider getMatchesData
     * @param string[] $names
     */
    public function testMatches(Item $item, array $names, bool $expected = true): void
    {
        self::assertEquals($expected, $this->createInstance()->matches($item, ...$names));
    }
}
