# Discommand2

Discommand2 is a PHP-based system designed to handle message queues and logging.

## Error Handling

The system includes robust error handling with custom exception classes for different error scenarios. These exceptions are logged and managed to ensure reliability and maintainability.

### Custom Exceptions

- `ConfigurationException`: Handles configuration-related errors.
- `NetworkException`: Handles network-related errors.
- `MessageQueueException`: Handles message queue-related errors.
- `LogException`: Handles logging-related errors.

### Global Exception Handler

A global exception handler is registered to catch unhandled exceptions, log them, and signal an error status to systemd.

## Testing

PHPUnit tests are provided in the `tests.d` directory to ensure that error handling works as expected.

## Systemd Integration

The application is designed to be managed by systemd, which will handle service start, stop, and restart, as well as capture the output of the logging to the systemd journal.

## Packaging

The application can be packaged as a PHAR for easy deployment across different environments.