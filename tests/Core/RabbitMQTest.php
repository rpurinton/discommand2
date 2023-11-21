<?php

declare(strict_types=1);

use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use PHPUnit\Framework\TestCase;
use RPurinton\Discommand\Core\RabbitMQ;
use RPurinton\Discommand\Core\Logger;
use React\EventLoop\Loop;
use RPurinton\Discommand\Exceptions\FatalException;

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
}
