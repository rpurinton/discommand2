<?php

namespace RPurinton\Discommand2;

require_once(__DIR__ . "/BunnyConsumer.php");

$loop = \React\EventLoop\Loop::get();
new BunnyConsumer($loop, "test", function ($data) {
    echo "Received: " . json_encode($data) . "\n";
    return true;
});
