<?php

namespace RPurinton\Discommand\Core;

use React\Async;
use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use RPurinton\Discommand\Exceptions\FatalException;

class MessageQueue
{
	private ?Client $client;
	private ?Channel $channel;
	private ?string $consumerTag;
	private ?string $queue;

	public function __construct(private Brain $brain, private callable $callback)
	{
		$this->brain->log("RabbitMQ initializing...");
		$this->connect() or throw new FatalException('Failed to establish the connection');
	}

	public function connect(): bool
	{
		$this->queue = $this->brain->myName;
		$this->consumerTag = bin2hex(random_bytes(8));
		$this->client = new Client($this->brain->loop, $this->brain->getConfig("bunny")) or throw new FatalException('Failed to establish the client');
		$this->client = Async\await($this->client->connect()) or throw new FatalException('Failed to establish the connection');
		$this->channel = Async\await($this->client->channel()) or throw new FatalException('Failed to establish the channel');
		Async\await($this->channel->qos(0, 1)) or throw new FatalException('Failed to set the QoS');
		Async\await($this->channel->queueDeclare($this->queue)) or throw new FatalException('Failed to declare the queue');
		$this->channel->consume($this->process(...), $this->queue, $this->consumerTag) or throw new FatalException('Failed to consume the queue');
		$this->brain->log("RabbitMQ consuming.") or throw new FatalException('Failed to log');
		return true;
	}

	public function process(Message $message, Channel $channel, Client $client)
	{
		unset($message->headers["delivery-mode"]);
		if (!isset($message->headers["Via"])) $message->headers["Via"] = "RabbitMQ";
		$message->headers["Content"] = $message->content;
		$this->brain->log("Received message " . trim(substr(print_r($message->headers, true), 6)));
		if (isset($message->headers["Die"]) && $message->headers["Die"]) {
			$this->brain->log("Received die message... D: goodbye cruel world.");
			$this->brain->log($this->queue . " has died. :(...");
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

	public function __destruct()
	{
		$this->disconnect();
	}
}
