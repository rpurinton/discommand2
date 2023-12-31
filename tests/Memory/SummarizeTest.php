<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand\Memory\Summarize;

class SummarizeTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            Summarize::class,
            new Summarize()
        );
    }
}
