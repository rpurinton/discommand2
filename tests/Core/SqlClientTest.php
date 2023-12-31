<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand\Core\SqlClient;

class SqlClientTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            SqlClient::class,
            new SqlClient('testBrain')
        );
    }
}
