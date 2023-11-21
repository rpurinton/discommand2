<?php

namespace RPurinton\Discommand2\Core;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\Memory\MemoryManager;
use RPurinton\Discommand2\OpenAI;
use RPurinton\Discommand2\Exceptions\Exception;
use RPurinton\Discommand2\Exceptions\FatalException;

class Brain extends SqlClient
{
    public ?MemoryManager $memory = null;
    public ?LoopInterface $loop = null;
    private ?RabbitMQ $bunny = null;
    private ?OpenAI\Client $ai = null;

    public function __construct(string $myName)
    {
        parent::__construct($myName) or throw new FatalException("Failed to initialize SQL client");
        $this->memory = new MemoryManager($this) or throw new FatalException("Failed to initialize MemoryManager");
        $this->loop = Loop::get() or throw new FatalException("Failed to initialize event loop");
        $this->ai = new OpenAI\Client($this) or throw new FatalException("Failed to initialize OpenAI client");
        $this->bunny = new RabbitMQ($this) or throw new FatalException("Failed to initialize RabbitMQ");
        $this->log("$myName is alive.") or throw new FatalException("Failed to log");
        $this->alive = true;
        return $this;
    }

    public function __destruct()
    {
        if ($this->bunny) $this->bunny->disconnect();
        if ($this->loop) $this->loop->stop();
    }

    private function inbox(array $message): bool
    {
        $this->memory->store($message) or throw new Exception("Failed to store message");
        if (isset($message["RSVP"]) && $message["RSVP"]) {
            $this->ai->requestHandler->handleRequest($message) or throw new Exception("Failed to handle request");
        }
        return true;
    }
}
