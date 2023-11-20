<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\FatalException;

class Client
{
    public $requestHandler;
    private $client;
    private $prompt;

    public function __construct(private Brain $brain, $token = null)
    {
        $this->requestHandler = new RequestHandler($brain);
        $config_file = "/home/" . $brain->myName . "/prompt.d/openai.json";
        if (!file_exists($config_file)) throw new FatalException("OpenAI configuration file not found at $config_file", true);
        $this->prompt = json_decode(file_get_contents($config_file), true);
        if (!$token) $token = $this->prompt["api_key"];
        unset($this->prompt["token"]);
        $this->validate_token($token);
        $this->client = \OpenAI::client($token);
        if (!$this->client) throw new FatalException("Failed to initialize OpenAI client");
        $brain->log("OpenAI connected.");
        return true;
    }

    private function validate_api_key($api_key)
    {
        if (substr($api_key, 0, 3) !== 'sk-' || strlen($api_key) !== 51) {
            throw new FatalException("Invalid OpenAI token");
        }
    }
}
