<?php

namespace RPurinton\Discommand2;

require_once(__DIR__ . "/ConfigLoader.php");

class BunnyConsumer extends ConfigLoader
{
	private $channel;
	private $callback;

	public function __construct(private $queue)
	{
		parent::__construct();
	}

	public function __destruct()
	{
		$this->channel->close();
	}

	public function run(callable $callback): void
	{
		$this->callback = $callback;
		$client = new \Bunny\Async\Client(\React\EventLoop\Loop::get(), $this->config["bunny"]);
		$client->connect()->then($this->getChannel(...))->then($this->consume(...));
	}

	private function getChannel($client)
	{
		return $client->channel();
	}

	private function consume($channel)
	{
		$this->channel = $channel;
		$channel->qos(0, 1);
		$channel->queueDeclare($this->queue);
		$channel->consume($this->process(...), $this->queue);
	}

	private function process($message, $channel, $client)
	{
		if (($this->callback)(json_decode($message->content, true))) {
			$channel->ack($message);
		} else {
			$channel->nack($message);
		}
	}

	public static function publish($queue, $data)
	{
		$json_string = json_encode($data);
		$descriptorspec = array(
			0 => array("pipe", "r"),
			1 => array("pipe", "w"),
			2 => array("pipe", "w")
		);
		$process = proc_open("/usr/local/bin/php -f " . __DIR__ . "/BunnyPublisher.php '$queue'", $descriptorspec, $pipes);
		if (is_resource($process)) {
			fwrite($pipes[0], $json_string);
			fclose($pipes[0]);
			fclose($pipes[1]);
			fclose($pipes[2]);
			proc_close($process);
		}
	}
}
