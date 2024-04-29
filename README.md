# Prerequisties

* Docker
* PHP
* Composer

# Setup

Clone the repository using git `git clone https://github.com/0zby/kanye_quotes.git`.

Change directory into the newly created repository folder `cd kanye_quotes`.

Install using composer `composer install`.

Copy the example environment file `cp .env.example .env`.

Launch the containers `./vendor/bin/sail up -d`.

Run the migrations `./vendor/bin/sail artisan migrate`.


# Running tests

## Automated tests

Tests are run simply with `./vendor/bin/sail artisan test`.

## Manual testing

Place a get request to `localhost/quotes/kanye` to retreive 5 of Kanye's quotes.

Place a patch request to `localhost/quotes/kanye` to reset the five quotes used (and also display the result).
