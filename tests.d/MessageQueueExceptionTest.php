<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\BunnyConsumer;
use RPurinton\Discommand2\Exceptions\MessageQueueException;
use React\EventLoop\LoopInterface;

class MessageQueueExceptionTest extends TestCase
{
    public function testMessageQueueException()
    {
        $this->expectException(MessageQueueException::class);
        $loop = $this->createMock(LoopInterface::class);
        $bunnyConsumer = new BunnyConsumer($loop, 'invalid_queue', function () {
        });
        // Intentionally trigger a MessageQueueException
        $bunnyConsumer->simulateFailedPublish();
    }
}
