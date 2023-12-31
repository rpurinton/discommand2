# Setup Guide

This guide provides instructions on how to set up the Discommand2 project on your system.

## Requirements

- Linux-based operating system (tested on CentOS 9)
- PHP 8.2 or higher
- Composer for dependency management
- RabbitMQ server
- MySQL server

## Installation

1. Clone the Discommand2 repository to your desired location.
2. Navigate to the project directory and run `composer install` to install the required dependencies.
3. Copy the example configuration files from `conf.d.example` to `conf.d` and update them with your specific settings.
4. Set up the RabbitMQ server with the vhost, user, and permissions as specified in `bunny.json`.
5. Create a MySQL database and user as specified in the configuration.
6. Run `scripts/newBrain.php` with a specified name to provision a new Brain instance.

## Starting the Service

- Use `scripts/runBrain.php` to start a Brain instance.
- The service can be managed via systemd if set up accordingly.

## Updating the Project

- Use the `pull` script to update the project from the git repository and set the correct permissions.

For more detailed instructions, refer to the specific sections within this documentation.