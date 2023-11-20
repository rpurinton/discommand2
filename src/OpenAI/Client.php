<?php

namespace RPurinton\Discommand2\OpenAI;

use RPurinton\Discommand2\OpenAI\PromptBuilder;
use RPurinton\Discommand2\OpenAI\StreamHandler;
use RPurinton\Discommand2\OpenAI\FunctionLoader;
use RPurinton\Discommand2\OpenAI\FunctionHandler;
use RPurinton\Discommand2\OpenAI\TokenCounter;
use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\OpenAIException;
use RPurinton\Discommand2\Exceptions\ConfigurationException;


class Client
{
    public $promptBuilder;
    public $streamHandler;
    public $functionLoader;
    public $functionHandler;
    public $tokenCounter;
    private $client;
    private $prompt;

    public function __construct(private Brain $brain, $token = null)
    {
        $this->promptBuilder = new PromptBuilder();
        $this->streamHandler = new StreamHandler();
        $this->functionLoader = new FunctionLoader();
        $this->functionHandler = new FunctionHandler();
        $this->tokenCounter = new TokenCounter();
        $config_file = "/home/" . $brain->myName . "/prompt.d/openai.json";
        if (!file_exists($config_file)) throw new ConfigurationException("OpenAI configuration file not found at $config_file");
        $this->prompt = json_decode(file_get_contents($config_file), true);
        if (!$token) $token = $this->prompt["token"];
        unset($this->prompt["token"]);
        $this->validate_token($token);
        $this->client = \OpenAI::client($token);
        if (!$this->client) throw new OpenAIException("Failed to initialize OpenAI client");
        $brain->log("OpenAI initialized");
    }

    private function validate_token($token)
    {
        if (!preg_match('/^sk-[a-zA-Z0-9]{32}$/', $token)) throw new OpenAIException("Invalid OpenAI token");
    }
}
