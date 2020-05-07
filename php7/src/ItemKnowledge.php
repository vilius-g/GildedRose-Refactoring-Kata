<?php

declare(strict_types=1);

namespace App;

/**
 * Keeps knowledge of items and provides various answers about them.
 */
final class ItemKnowledge
{
    /**
     * @var ItemMatcher
     */
    private ItemMatcher $itemMatcher;

    /**
     * ItemKnowledge constructor.
     */
    public function __construct(ItemMatcher $itemMatcher)
    {
        $this->itemMatcher = $itemMatcher;
    }

    /**
     * Return whether item has passed its sell_in date.
     */
    public function isExpired(Item $item): bool
    {
        return $item->sell_in < 0;
    }

    /**
     * Is item legendary and cannot be altered.
     */
    public function isLegendary(Item $item): bool
    {
        return $this->itemMatcher->matches($item, KnownItemName::SULFURAS);
    }

    /**
     * Does item quality increase with age.
     */
    public function qualityIncreasesWithAge(Item $item): bool
    {
        return $this->itemMatcher->matches($item, KnownItemName::AGED_BRIE, KnownItemName::BACKSTAGE_PASSES);
    }

    /**
     * Does quality still increase after sell by date.
     */
    public function qualityIncreasesAfterExpiration(Item $item): bool
    {
        return $this->itemMatcher->matches($item, KnownItemName::AGED_BRIE);
    }

    /**
     * Does quality reset to zero after sell by date.
     */
    public function qualityResetsAfterExpiration(Item $item): bool
    {
        return $this->itemMatcher->matches($item, KnownItemName::BACKSTAGE_PASSES);
    }

    /**
     * Return how much the quality should be increased.
     */
    public function getQualityIncrement(Item $item): int
    {
        if ($this->itemMatcher->matches($item, KnownItemName::BACKSTAGE_PASSES)) {
            if ($item->sell_in <= 5) {
                return 3;
            }
            if ($item->sell_in <= 10) {
                return 2;
            }
        }

        return 1;
    }

    /**
     * Return how much the quality should be decreased.
     */
    public function getQualityDecrement(Item $item): int
    {
        if ($this->itemMatcher->matches($item, KnownItemName::CONJURED)) {
            return 2;
        }

        return 1;
    }
}
