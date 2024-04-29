# Prerequisties

* Docker
* PHP
* Composer

# Setup

Clone the repository using git.

Change directory into the newly created repository folder.

Install using composer `composer install`.

Copy the example environment file `cp .env.example .env`.

Launch the containers `./vendor/bin/sail up -d`.


# Running tests

## Automated tests

Tests are run simply with `./vendor/bin/sail artisan test`.

## Manual testing

Place a get request to `localhost/quotes/kanye` to retreive 5 of Kanye's quotes.

Place a patch request to `localhost/quotes/kanye` to reset the five quotes used (and also display the result).
