# Discommand2

Discommand2 is an advanced message processing system designed to handle asynchronous tasks with high efficiency and reliability. It leverages RabbitMQ for message queuing, MariaDB for data persistence, and PHP for the core application logic.

## Features

- Asynchronous message processing with RabbitMQ.
- Robust logging system for easy debugging and monitoring.
- Custom exception handling for granular error management.
- Scalable architecture to handle increasing loads.
- Comprehensive test suite for reliability.

## Components

- `Brain`: The central processing unit of the system, handling all message-related operations.
- `BunnyConsumer`: A RabbitMQ consumer for processing queued messages.
- `Logger`: A logging utility to record system events and errors.
- `ConfigLoader`: A configuration manager to handle system settings.
- `GlobalExceptionHandler`: An exception handler to manage unexpected errors.

## Getting Started

Refer to the [docs.d](https://github.com/rpurinton/discommand2/tree/master/docs.d) directory for detailed documentation on setting up, configuring, and using Discommand2. The documentation includes a setup guide, usage instructions, technical references, testing information, troubleshooting tips, and contribution guidelines.

## Contributing

Contributions are welcome! Please refer to the `docs.d/Contributing.md` for contribution guidelines.

## License

Discommand2 is open-source software licensed under the MIT license. See the full license file at [LICENSE](https://github.com/rpurinton/discommand2/blob/master/LICENSE).
