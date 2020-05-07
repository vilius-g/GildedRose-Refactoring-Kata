<?php

declare(strict_types=1);

namespace App;

use function in_array;

/**
 * Matches items against known ones.
 */
final class ItemMatcher
{
    /**
     * Match item against known name
     */
    public function matches(Item $item, string ...$name): bool
    {
        return in_array($item->name, $name, true);
    }
}
