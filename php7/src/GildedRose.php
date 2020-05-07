<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use function max;
use function min;

final class GildedRose
{
    private const QUALITY_MAX = 50;
    private const QUALITY_MIN = 0;
    /** @var Item[] */
    private array $items;
    /**
     * @var ItemKnowledge
     */
    private ItemKnowledge $itemKnowledge;

    /**
     * GildedRose constructor.
     * @param Item[] $items
     * @param ItemKnowledge $itemKnowledge
     */
    public function __construct(array $items, ItemKnowledge $itemKnowledge = null)
    {
        $this->items = $items;
        $this->itemKnowledge = $itemKnowledge ?? new ItemKnowledge();
    }

    /**
     * Adjust quality values for all items after each day.
     */
    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->updateItemQuality($item);
        }
    }

    /**
     * Adjust quality for single item.
     */
    private function updateItemQuality(Item $item): void
    {
        if ($this->itemKnowledge->isLegendary($item)) {
            return;
        }

        if ($this->itemKnowledge->qualityIncreasesWithAge($item)) {
            $this->increaseQuality($item, $this->itemKnowledge->getQualityIncrement($item));
        } else {
            $this->decreaseQuality($item);
        }

        $this->decreaseSellIn($item);

        if ($this->itemKnowledge->isExpired($item)) {
            if ($this->itemKnowledge->qualityIncreasesAfterExpiration($item)) {
                $this->increaseQuality($item);
            } elseif ($this->itemKnowledge->qualityResetsAfterExpiration($item)) {
                $this->resetQuality($item);
            } else {
                $this->decreaseQuality($item);
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
     * Decrease quality by one but only if allowed for this item.
     *
     * @param Item $item
     */
    private function decreaseQuality(Item $item): void
    {
        $item->quality = max($item->quality - 1, self::QUALITY_MIN);
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
     * Increase quality but only if allowed.
     *
     * @param Item $item
     * @param int $amount
     */
    private function increaseQuality(Item $item, int $amount = 1): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('$amount must be positive: '.$amount);
        }

        $item->quality = min($item->quality + $amount, self::QUALITY_MAX);
    }
}
