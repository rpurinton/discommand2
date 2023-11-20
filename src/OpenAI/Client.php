<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\FatalException;

class Client
{
    public $requestHandler;
    private $client;

    public function __construct(private Brain $brain)
    {
        $this->requestHandler = new RequestHandler($brain);
        $this->client = \OpenAI::client($this->requestHandler->promptBuilder->config->api_key) or throw new FatalException("Failed to initialize OpenAI client");
        if (!$this->client) throw new FatalException("Failed to initialize OpenAI client");
        $brain->log("OpenAI connected.");
    }
}
