<?php

namespace RPurinton\Discommand\OpenAI;

use RPurinton\Discommand\Core\Brain;
use RPurinton\Discommand\Exceptions\FatalException;

class AI
{
    private $client;

    public function __construct(private Brain $brain)
    {
        $this->client = \OpenAI::client($this->brain->config->openai->api_key) or throw new FatalException("Failed to initialize OpenAI client");
        if (!$this->client) throw new FatalException("Failed to initialize OpenAI client");
        $brain->log("OpenAI connected.");
    }
}
