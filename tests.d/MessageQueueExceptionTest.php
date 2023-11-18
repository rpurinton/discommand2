<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\BunnyConsumer;
use RPurinton\Discommand2\Exceptions\MessageQueueException;

class MessageQueueExceptionTest extends TestCase
{
    public function testMessageQueueException()
    {
        $this->expectException(MessageQueueException::class);
        $bunnyConsumer = new BunnyConsumer(null, 'invalid_queue', function() {});
        // Intentionally trigger a MessageQueueException
        $bunnyConsumer->publishToInvalidQueue([]);
    }
}
