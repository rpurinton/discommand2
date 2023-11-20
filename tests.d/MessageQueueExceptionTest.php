<?php

use PHPUnit\Framework\TestCase;
use PSpell\Config;
use RPurinton\Discommand2\Core\RabbitMQ;
use RPurinton\Discommand2\Core\ConfigLoader;
use RPurinton\Discommand2\Exceptions\MessageQueueException;
use React\EventLoop\LoopInterface;

class MessageQueueExceptionTest extends TestCase
{
    public function testMessageQueueException()
    {
        $this->expectException(MessageQueueException::class);
        $loop = $this->createMock(LoopInterface::class);
        $config = new ConfigLoader('testBrain');
        $rabbitmq = new RabbitMQ($config->getConfig("bunny"), $loop, 'invalid_queue', function () {
            // Do nothing
        }, $config->getLogger());
    }
}
