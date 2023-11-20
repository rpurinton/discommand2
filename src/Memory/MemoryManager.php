<?php

namespace RPurinton\Discommand2\Memory;

use RPurinton\Discommand2\Core\Logger;

class MemoryManager
{
    private ?Recall $recall;
    private ?Store $store;
    private ?Summarize $summarize;

    public function __construct(private Logger $brain)
    {
        $this->recall = new Recall();
        $this->store = new Store();
        $this->summarize = new Summarize();
        $brain->log("MemoryManager initialized.");
    }
}
