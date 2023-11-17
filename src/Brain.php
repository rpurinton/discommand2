<?php

namespace RPurinton\Discommand2;

require_once(__DIR__ . "/BunnyConsumer.php");

class Brain extends ConfigLoader
{
    private $bunny = null;

    public function __construct(private $myName)
    {
        parent::__construct();
        $this->bunny = new BunnyConsumer($this->myName);
        $this->bunny->publish($this->myName, ["name" => $this->myName, "type" => "register"]);
    }

    public function run()
    {
        $this->bunny->run($this->inbox(...));
    }

    private function inbox($message): bool
    {
        print_r($message);
        return true;
    }
}

if (!isset($argv[1])) die("Usage: php src/Brain.php <name>\n");
$myName = $argv[1];

$brain = new Brain($myName);
$brain->run();
