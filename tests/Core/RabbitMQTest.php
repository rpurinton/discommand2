<?php

declare(strict_types=1);

use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\RabbitMQ;
use RPurinton\Discommand2\Core\Logger;
use React\EventLoop\Loop;
use RPurinton\Discommand2\Exceptions\FatalException;

class RabbitMQTest extends TestCase
{
    public function testConstructorWithInvalidHost(): void
    {
        $this->expectException(FatalException::class);
        $this->expectExceptionMessage('Failed to connect to the server');

        $options = ['host' => 'invalid'];
        $loop = Loop::get();
        $logger = new Logger('testBrain');
        new RabbitMQ($options, $loop, function ($message) {
        }, $logger);
    }

    public function testConstructorWithInvalidQueue(): void
    {
        $this->expectException(FatalException::class);
        $this->expectExceptionMessage('Failed to declare queue');

        $options = ['host' => 'valid'];
        $loop = Loop::get();
        $logger = new Logger('testBrain');
        $logger->myName = 'invalid_queue';
        new RabbitMQ($options, $loop, function ($message) {
        }, $logger);
    }

    public function testProcess(): void
    {
        // Mock the Message, Channel, and Client classes
        $messageMock = $this->createMock(Message::class);
        $channelMock = $this->createMock(Channel::class);
        $clientMock = $this->createMock(Client::class);

        $options = ['host' => 'valid'];
        $loop = Loop::get();
        $logger = new Logger('testBrain');
        $rabbitmq = new RabbitMQ($options, $loop, function ($message) {
        }, $logger, $clientMock);

        // Call the process method and check that no exceptions are thrown
        $this->expectNotToPerformAssertions();
        $rabbitmq->process($messageMock, $channelMock, $clientMock);
    }
}
