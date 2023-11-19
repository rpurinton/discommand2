<?php

namespace RPurinton\Discommand2;

use TikToken\Encoder;

class TokenCounter
{
    private $encoder;
    public function __construct()
    {
        $this->encoder = new Encoder();
    }

    public function count($text)
    {
        return count($this->encoder->encode($text));
    }
}
