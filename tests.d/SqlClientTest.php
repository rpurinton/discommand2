<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\SqlClient;
use RPurinton\Discommand2\Exceptions\SqlException;

class SqlClientTest extends TestCase
{
    private $sqlClient;

    protected function setUp(): void
    {
        $this->sqlClient = new SqlClient("valid-brain-name");
    }

    protected function tearDown(): void
    {
        $this->sqlClient = null;
    }

    public function testConstruct()
    {
        $this->assertInstanceOf(SqlClient::class, $this->sqlClient);
    }

    public function testConnect()
    {
        $this->expectException(SqlException::class);
        $this->sqlClient = new SqlClient("invalid-brain-name");
    }

    public function testQuery()
    {
        $this->expectException(SqlException::class);
        $this->sqlClient->query("invalid-query");
    }
}
