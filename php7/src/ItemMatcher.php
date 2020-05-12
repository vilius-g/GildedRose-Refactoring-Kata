<?php

declare(strict_types=1);

namespace App;

use function preg_match;

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
        foreach ($name as $pattern) {
            if ($this->nameMatchesPattern($item->name, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private function nameMatchesPattern(string $name, string $pattern): bool
    {
        return 1 === preg_match($pattern, $name);
    }
}
