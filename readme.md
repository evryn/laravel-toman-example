# Laravel Toman Example

This project contains a very simple usage of [laravel-toman](https://evryn.github.io/laravel-toman/) package.

## Setup Project

Clone this repository somewhere on your local machine:
```bash
git clone git@github.com:evryn/laravel-toman-example.git
cd laravel-toman-example
```

Install dependencies:
```bash
composer install
```

## Setup Laravel

Copy `.env.example` file to `.env` and add following changes:
```dotenv
...

APP_URL=http://laravel-toman.online
DB_CONNECTION=sqlite
```

A simple sqlite file is used for database management. You're free to use MySQL or whatever you need. Create a file:
```bash
# on Linux
touch database/database.sqlite
# on Windows
type nul > database/database.sqlite
```

Run following command to make new keys:
```bash
php artisan key:generate
```

> ⚠ Make sure `laravel-toman.online` is pointed to `public` directory correctly since we need a valid URL to handle callbacks. While you're not seeing `http://laravel-toman.online`, don't read future steps.

## Setup Toman

Edit your `.env` file and add following changes:
```dotenv
...

TOMAN_GATEWAY=zarinpal
ZARINPAL_MERCHANT_ID=xxxx-xxxxx-xxxxx-xxxxx # Your real merchant ID
ZARINPAL_SANDBOX=true # Or false of course. See docs.
```

## Play with it

1- Visit `http://laravel-toman.online/new-payment` and fill the form.
2- Accept, reject and do whatever you want!

## Testing

Run tests:
```bash
composer test
```

# Files to look at
Here is main files that you should look at as an example:
```
app/Http/Controllers/PaymentController.php
tests/Feature/PaymentTest.php
database/migrations/2019_10_15_162921_create_payments_table
```
