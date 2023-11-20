# Testing

This document provides information on how to run tests and add new tests to the Discommand2 project.

## Running Tests

- The project uses PHPUnit for testing.
- To run the tests, navigate to the project directory and execute `vendor/bin/phpunit`.

## Test Files

- Test files are located in the `tests.d` directory.
- Each test file corresponds to a specific class or functionality within the project.

## Adding New Tests

- To add a new test, create a new file in the `tests.d` directory following the naming convention `<ClassName>Test.php`.
- Write test methods using the PHPUnit framework.
- Ensure that each test method is prefixed with `test` and clearly describes the behavior it tests.

For more detailed information on writing tests, refer to the PHPUnit documentation.