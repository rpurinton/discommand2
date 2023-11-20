<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\FatalException;

class Client
{
    public $requestHandler;
    private $client;

    public function __construct(private Brain $brain, ?string $api_key = null, ?int $history_tokens = null)
    {
        $this->requestHandler = new RequestHandler($brain, $api_key, $history_tokens);
        $this->client = \OpenAI::client($this->requestHandler->promptBuilder->api_key);
        if (!$this->client) throw new FatalException("Failed to initialize OpenAI client");
        $brain->log("OpenAI connected.");
    }
}
