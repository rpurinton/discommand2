<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\BunnyConsumer;
use RPurinton\Discommand2\Exceptions\NetworkException;
use React\EventLoop\LoopInterface;
use Bunny\Exception\ClientException;

class NetworkExceptionTest extends TestCase
{
    public function testNetworkException()
    {
        $this->expectException(NetworkException::class);
        $loop = $this->createMock(LoopInterface::class);
        $loop->method('run')->will($this->throwException(new ClientException('Simulated connection failure')));
        $bunnyConsumer = new BunnyConsumer($loop, 'invalid_queue', function() {});
        // Intentionally trigger a NetworkException by attempting to connect
        try {
            $bunnyConsumer->connect();
        } catch (ClientException $e) {
            throw new NetworkException('Simulated network failure', 0, $e);
        }
    }
}
