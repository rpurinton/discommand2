<?php

namespace RPurinton\Discommand2\OpenAI;

class Client
{
    public function __construct()
    {
        $this->promptBuilder = new PromptBuilder();
        $this->streamHandler = new StreamHandler();
        $this->functionLoader = new FunctionLoader();
        $this->functionHandler = new FunctionHandler();
    }
}
