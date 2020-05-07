<?php

declare(strict_types=1);

namespace App;

use function in_array;

/**
 * Keeps knowledge of items and provides various answers about them.
 */
final class ItemKnowledge
{
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
        return $this->itemMatches($item, KnownItemName::SULFURAS);
    }

    /**
     * Does item quality increase with age.
     */
    public function qualityIncreasesWithAge(Item $item): bool
    {
        return $this->itemMatches($item, KnownItemName::AGED_BRIE, KnownItemName::BACKSTAGE_PASSES);
    }

    /**
     * Does quality still increase after sell by date.
     */
    public function qualityIncreasesAfterExpiration(Item $item): bool
    {
        return $this->itemMatches($item, KnownItemName::AGED_BRIE);
    }

    /**
     * Does quality reset to zero after sell by date.
     */
    public function qualityResetsAfterExpiration(Item $item): bool
    {
        return $this->itemMatches($item, KnownItemName::BACKSTAGE_PASSES);
    }

    /**
     * Return how much the quality should be increased.
     */
    public function getQualityIncrement(Item $item): int
    {
        if ($this->itemMatches($item, KnownItemName::BACKSTAGE_PASSES)) {
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
        if ($this->itemMatches($item, KnownItemName::CONJURED)) {
            return 2;
        }

        return 1;
    }

    /**
     * Match item against known name
     */
    private function itemMatches(Item $item, string ...$name): bool
    {
        return in_array($item->name, $name, true);
    }
}
