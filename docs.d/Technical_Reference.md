# Technical Reference

This section provides detailed information about the Discommand2 codebase, including class and method references.

## Classes

- `Brain`: The main class that initializes the system and handles message processing.
- `BunnyConsumer`: Manages interactions with the RabbitMQ server for message queuing.
- `ConfigLoader`: Loads configuration settings from the `conf.d` directory.
- `Logger`: Handles logging of messages to files.
- `GlobalExceptionHandler`: Catches and logs unhandled exceptions.

## Exceptions

- `ConfigurationException`: Thrown when there is an issue with the configuration.
- `NetworkException`: Thrown when there is a network-related issue.
- `MessageQueueException`: Thrown when there is an issue with the message queue.
- `LogException`: Thrown when there is an issue with logging.

## Scripts

- `runBrain.php`: Starts a Brain instance.
- `newBrain.php`: Provisions a new Brain instance.
- `sendCrashAlert.php`: Sends an email alert when a Brain service crashes.

For more detailed information on each class and script, refer to the source code and comments within the `src` directory.