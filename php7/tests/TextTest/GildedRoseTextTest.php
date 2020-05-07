<?php

declare(strict_types=1);

namespace App\Tests\TextTest;

use function file_get_contents;
use function ob_get_clean;
use function ob_start;
use PHPUnit\Framework\TestCase;

class GildedRoseTextTest extends TestCase
{
    public function testUsingTextTest(): void
    {
        $expectedContent = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'GildedRoseTextTest.txt');
        $actualContent = $this->runTextTestFixture();
        self::assertEquals($expectedContent, $actualContent);
    }

    /**
     * Run fixture file and return its output.
     */
    private function runTextTestFixture(): string
    {
        ob_start();
        $argv = ['texttest_fixture.php', '11'];
        include __DIR__.'/../../fixtures/texttest_fixture.php';

        return ob_get_clean();
    }
}
