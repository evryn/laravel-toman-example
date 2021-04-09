# Laravel Toman Example
This repository contains a simple project with [Laravel Toman](https://github.com/evryn/laravel-toman) implementation.

## Setup
Clone this repo:
```bash
git clone git@github.com:evryn/laravel-toman-example.git
```

Copy `.env.example` to `.env`

Install packages:
```bash
docker-compose run --rm composer install --ignore-platform-reqs
# or: composer install 
```

Serve it:
```bash
docker-compose up
# or: php artisan serve --port=8000
```

Start with this route:
[localhost:8000/payment](localhost:8000/payment)
