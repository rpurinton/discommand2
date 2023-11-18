<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\BunnyConsumer;
use RPurinton\Discommand2\Exceptions\NetworkException;

class NetworkExceptionTest extends TestCase
{
    public function testNetworkException()
    {
        $this->expectException(NetworkException::class);
        $bunnyConsumer = new BunnyConsumer(null, 'invalid_queue', function() {});
        // Intentionally trigger a NetworkException
        $bunnyConsumer->connectToInvalidServer();
    }
}
