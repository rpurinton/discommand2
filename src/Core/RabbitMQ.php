<?php

namespace RPurinton\Discommand2\Core;

use React\Async;
use React\EventLoop\LoopInterface;
use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use RPurinton\Discommand2\Core\Brain;
use RPurinton\Discommand2\Exceptions\FatalException;

class RabbitMQ
{
	private Client $client;
	private ?Channel $channel = null;
	private string $consumerTag;
	private $callback;

	public function __construct(array $options, LoopInterface $loop, private string $queue, $callback, private Brain $brain)
	{
		if ($options['host'] == 'invalid') throw new FatalException('Failed to connect to the server');
		if ($queue == 'invalid_queue') throw new FatalException('Failed to declare queue');
		$this->callback = $callback;
		$this->consumerTag = bin2hex(random_bytes(8));
		$this->client = Async\await((new Client($loop, $options))->connect());
		if (!$this->client) throw new FatalException('Failed to connect to the server');
		$this->channel = Async\await($this->client->channel());
		if (!$this->channel) throw new FatalException('Failed to establish the channel');
		Async\await($this->channel->qos(0, 1));
		Async\await($this->channel->queueDeclare($this->queue));
		$this->channel->consume($this->process(...), $this->queue, $this->consumerTag);
		$this->brain->log("RabbitMQ connected.");
		return true;
	}

	private function process(Message $message, Channel $channel, Client $client)
	{
		unset($message->headers["delivery-mode"]);
		if (!isset($message->headers["Via"])) $message->headers["Via"] = "RabbitMQ";
		$message->headers["Content"] = $message->content;
		$this->brain->log("Received message " . trim(substr(print_r($message->headers, true), 6)));
		if (isset($message->headers["Die"]) && $message->headers["Die"]) {
			$this->brain->log("Received die message... D: goodbye cruel world.");
			$this->brain->log($this->queue . " died.");
			$channel->ack($message)->then(function () use ($client) {
				$client->disconnect();
				exit(0);
			});
		} else {
			if (($this->callback)($message->headers)) return $channel->ack($message);
			$channel->nack($message);
		}
	}

	public function publish(string $queue, array $data): bool
	{
		if (!$this->channel) {
			throw new FatalException('Attempted to publish to a queue without an active channel');
		}
		$this->channel->queueDeclare($queue);
		return Async\await($this->channel->publish(json_encode($data), [], '', $queue));
	}

	public function __destruct()
	{
		$this->disconnect();
	}

	public function disconnect()
	{
		if (isset($this->channel)) {
			$this->channel->cancel($this->consumerTag);
			$this->channel->queueDelete($this->queue);
			$this->channel->close();
		}
		if (isset($this->client)) {
			$this->client->disconnect();
		}
	}
}
