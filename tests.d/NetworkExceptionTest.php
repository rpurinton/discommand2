<?php

use PHPUnit\Framework\TestCase;
use RPurinton\Discommand2\Core\ConfigLoader;
use RPurinton\Discommand2\Core\RabbitMQ;
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
        $config = new ConfigLoader('testBrain');
        $options = $config->getConfig("bunny");
        $options['host'] = 'invalid';
        $rabbitmq = new RabbitMQ($options, $loop, 'testBrain', function () {
            // Do nothing
        }, $config->getLogger());
    }
}
