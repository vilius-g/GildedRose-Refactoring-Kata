<?php

declare(strict_types=1);

namespace App;

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
        return KnownItemName::SULFURAS === $item->name;
    }

    /**
     * Does item quality increase with age.
     */
    public function qualityIncreasesWithAge(Item $item): bool
    {
        return in_array($item->name, [KnownItemName::AGED_BRIE, KnownItemName::BACKSTAGE_PASSES], true);
    }

    /**
     * Does quality still increase after sell by date.
     */
    public function qualityIncreasesAfterExpiration(Item $item): bool
    {
        return KnownItemName::AGED_BRIE === $item->name;
    }

    /**
     * Does quality reset to zero after sell by date.
     */
    public function qualityResetsAfterExpiration(Item $item): bool
    {
        return KnownItemName::BACKSTAGE_PASSES === $item->name;
    }

    /**
     * Return how much the quality should be increased.
     */
    public function getQualityIncrement(Item $item): int
    {
        if (KnownItemName::BACKSTAGE_PASSES === $item->name) {
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
        if (KnownItemName::CONJURED === $item->name) {
            return 2;
        }

        return 1;
    }
}
