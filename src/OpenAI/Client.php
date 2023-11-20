<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\FatalException;

class Client
{
    public $requestHandler;
    private $client;

    public function __construct(private Brain $brain, $token = null)
    {
        $this->requestHandler = new RequestHandler($brain);
        $this->client = \OpenAI::client($token);
        if (!$this->client) throw new FatalException("Failed to initialize OpenAI client");
        $brain->log("OpenAI connected.");
        return true;
    }
}
