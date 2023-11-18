<?php

namespace RPurinton\Discommand2;

use RPurinton\Discommand2\ConfigLoader;
use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use React\EventLoop\Loop;
use React\Async;

class BunnyConsumer extends ConfigLoader
{
	private $loop;
	private $queue;
	private $client;
	private $channel;
	private $callback;
	private $consumerTag;

	public function __construct()
	{
		echo ("Consumer Construct\n");
		parent::__construct();
		$this->loop = Loop::get();
		$this->client = new Client($this->loop, $this->config["bunny"]);
		$this->consumerTag = bin2hex(random_bytes(8));
		Async\Await($this->client->connect()->then($this->connected(...)));
	}

	public function __destruct()
	{
		echo ("Consumer Destruct\n");
		if ($this->channel) {
			$this->channel->cancel($this->consumerTag);
			$this->channel->queueDelete($this->queue);
			$this->channel->close();
		}
		if ($this->client) $this->client->disconnect();
		parent::__destruct();
	}

	private function connected(Client $client)
	{
		echo ("Consumer Connected\n");
		$client->channel()->then($this->channelReady(...));
	}

	private function channelReady(Channel $channel)
	{
		echo ("Consumer Channel Ready\n");
		$this->channel = $channel;
		$this->channel->qos(0, 1)->then($this->qosReady(...));
	}

	private function qosReady()
	{
		echo ("Consumer QOS Ready\n");
	}

	public function run(string $queue, callable $callback): void
	{
		echo ("Consumer Run\n");
		$this->queue = $queue;
		$this->callback = $callback;
		$this->channel->queueDeclare($queue);
		$this->channel->consume($this->process(...), $this->queue, $this->consumerTag);
	}

	private function process(Message $message, Channel $channel, Client $client)
	{
		echo ("Consumer Process\n");
		if (($this->callback)(json_decode($message->content, true))) return $channel->ack($message);
		$channel->nack($message);
	}

	public function publish($queue, $data)
	{
		echo ("Consumer Publish\n");
		$json_string = json_encode($data);
		$this->channel->queueDeclare($queue);
		$this->channel->publish($json_string, [], '', $queue);
	}
}
