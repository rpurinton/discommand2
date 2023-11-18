<?php

namespace RPurinton\Discommand2;

require_once(__DIR__ . "/ConfigLoader.php");

class BunnyPublisher extends ConfigLoader
{
    private $bunny;
    private $channel;

    public function __construct()
    {
        parent::__construct();
        $this->bunny = new \Bunny\Client($this->config['bunny']);
        $this->bunny->connect();
        $this->channel = $this->bunny->channel();
    }

    public function publish($queue, $json_string)
    {
        $this->channel->queueDeclare($queue);
        $this->channel->publish($json_string, [], '', $queue);
        $this->channel->close();
        $this->bunny->disconnect();
    }
}

if (!isset($argv[1])) die("Usage: php src/BunnyPublisher.php <queue>\nThen send JSON string on STDIN\n");
$queue = $argv[1];
$json_string = file_get_contents("php://stdin");
$bunnyPublisher = new BunnyPublisher();
$bunnyPublisher->publish($queue, $json_string);
