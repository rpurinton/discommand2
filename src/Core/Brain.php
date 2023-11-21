<?php

namespace RPurinton\Discommand\Core;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use RPurinton\Discommand\Memory\MemoryManager;
use RPurinton\Discommand\OpenAI\AI;
use RPurinton\Discommand\Exceptions\FatalException;

class Brain extends SqlClient
{
    public bool $alive = false;
    public ?MemoryManager $memory = null;
    public ?LoopInterface $loop = null;
    private ?MQ $mq = null;
    private ?AI $ai = null;

    public function __construct(string $myName)
    {
        parent::__construct($myName) or throw new FatalException("Failed to initialize SQL client");
        $this->memory = new MemoryManager($this) or throw new FatalException("Failed to initialize MemoryManager");
        $this->loop = Loop::get() or throw new FatalException("Failed to initialize event loop");
        $this->ai = new AI($this) or throw new FatalException("Failed to initialize OpenAI client");
        $this->mq = new MessageQueue($this) or throw new FatalException("Failed to initialize RabbitMQ");
        $this->log("$myName is alive.") or throw new FatalException("Failed to log");
        $this->alive = true;
        return $this;
    }

    public function __destruct()
    {
        if ($this->mq) $this->mq->disconnect();
        if ($this->loop) $this->loop->stop();
    }
}
