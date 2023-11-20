<?php

namespace RPurinton\Discommand2\Core;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand2\Memory\MemoryManager;
use RPurinton\Discommand2\OpenAI;
use RPurinton\Discommand2\OpenAI\TokenCounter;
use RPurinton\Discommand2\Exceptions\Exception;
use RPurinton\Discommand2\Exceptions\FatalException;

class Brain extends SqlClient
{
    private ?MemoryManager $memory = null;
    private ?LoopInterface $loop = null;
    private ?RabbitMQ $bunny = null;
    private ?OpenAI\Client $ai = null;
    private ?TokenCounter $tokenCounter = null;

    public function __construct(string $myName)
    {
        parent::__construct($myName) or throw new FatalException("Failed to initialize SQL client");
        $this->memory = new MemoryManager() or throw new FatalException("Failed to initialize MemoryManager");
        $this->loop = Loop::get() or throw new FatalException("Failed to initialize event loop");
        $this->bunny = new RabbitMQ($this->getConfig("bunny"), $this->loop, $this->inbox(...), $this) or throw new FatalException("Failed to initialize RabbitMQ");
        $this->ai = new OpenAI\Client($this) or throw new FatalException("Failed to initialize OpenAI client");
        $this->tokenCounter = new TokenCounter() or throw new FatalException("Failed to initialize TokenCounter");
        $this->alive = true;
        $this->log("$myName is alive.") or throw new FatalException("Failed to log");
        return $this;
    }

    public function __destruct()
    {
        if ($this->bunny) $this->bunny->disconnect();
        if ($this->loop) $this->loop->stop();
    }

    private function inbox(array $message): bool
    {
        $this->MemoryM
    }
}
