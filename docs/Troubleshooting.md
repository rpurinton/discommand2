# Troubleshooting

This document outlines common issues that may arise while working with the Discommand2 project and their potential solutions.

## Common Issues

- **Service Not Starting**: Ensure all configuration files are correct and all services (RabbitMQ, MySQL) are running.
- **Message Queue Errors**: Check the RabbitMQ logs and ensure the `BunnyConsumer` is configured correctly.
- **Logging Errors**: Verify the log directory exists and is writable by the web server user.
- **Network Errors**: Ensure the system has proper network connectivity and the RabbitMQ server is reachable.

## Debugging

- Check the log files in the `logs.d` directory for error messages.
- Use the `GlobalExceptionHandler` to catch and log unhandled exceptions.

## Reporting Issues

- If you encounter an issue that is not covered by this guide, please report it on the project's issue tracker with detailed information.