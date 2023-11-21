<?php

namespace RPurinton\Discommand\Memory;

use RPurinton\Discommand\Core\Brain;

class MemoryManager
{
    private ?Recall $recall;
    private ?Store $store;
    private ?Summarize $summarize;

    public function __construct(private Brain $brain)
    {
        $this->recall = new Recall();
        $this->store = new Store();
        $this->summarize = new Summarize();
        $brain->log("MemoryManager initialized.");
    }

    public function store(array $message): bool
    {
        return $this->store->store($message);
    }

    public function recall(string $key): array
    {
        return $this->recall->recall($key);
    }

    public function summarize(string $key): bool
    {
        return $this->summarize->summarize($key);
    }
}
