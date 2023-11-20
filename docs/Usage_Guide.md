# Usage Guide

This guide provides information on how to use the Discommand2 project and its features.

## Starting the Brain Service

- Execute `runBrain.php <name>` to start a Brain instance with the specified name.

## Interacting with the Message Queue

- The `BunnyConsumer` class is used to interact with the RabbitMQ server.
- Messages can be published to the queue and consumed from the queue using this class.

## Logging

- The `Logger` class is responsible for logging messages to the log files.
- Logs are stored in the `logs.d` directory.

## Error Handling

- Custom exceptions are used to handle various error scenarios.
- The `GlobalExceptionHandler` is registered to catch unhandled exceptions and log them.

## Configuration

- Configuration files are stored in the `conf.d` directory.
- The `ConfigLoader` class is used to load and manage these configurations.

For more detailed information on each feature, refer to the Technical Reference section.