<?php

declare(strict_types=1);

namespace App;

final class GildedRose
{
    /** @var Item[] */
    private array $items;
    /**
     * @var ItemKnowledge
     */
    private ItemKnowledge $itemKnowledge;
    /**
     * @var ItemManipulator
     */
    private ItemManipulator $itemManipulator;

    /**
     * GildedRose constructor.
     * @param Item[] $items
     * @param ItemKnowledge $itemKnowledge
     */
    public function __construct(
        array $items,
        ItemKnowledge $itemKnowledge = null,
        ItemManipulator $itemManipulator = null
    ) {
        $this->items = $items;
        $this->itemKnowledge = $itemKnowledge ?? new ItemKnowledge();
        $this->itemManipulator = $itemManipulator ?? new ItemManipulator();
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
            $this->itemManipulator->increaseQuality($item, $this->itemKnowledge->getQualityIncrement($item));
        } else {
            $this->itemManipulator->decreaseQuality($item, $this->itemKnowledge->getQualityDecrement($item));
        }

        $this->itemManipulator->decreaseSellIn($item);

        if ($this->itemKnowledge->isExpired($item)) {
            if ($this->itemKnowledge->qualityIncreasesAfterExpiration($item)) {
                $this->itemManipulator->increaseQuality($item);
            } elseif ($this->itemKnowledge->qualityResetsAfterExpiration($item)) {
                $this->itemManipulator->resetQuality($item);
            } else {
                $this->itemManipulator->decreaseQuality($item, $this->itemKnowledge->getQualityDecrement($item));
            }
        }
    }
}
