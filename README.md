# restful_api
[![Build Status](https://travis-ci.org/besingamkb/restful_api.svg?branch=master)](https://travis-ci.org/besingamkb/restful_api)

simple restful api using passport for authentication and with phpunit for unit testing and integration testing

#installations

## server requirements

1. ubuntu 18.04 bionic
2. php 7.2
3. mysql

## configure the application
1. `cp .env.example .env`
2. configure your database credential on `.env`
3. `composer install`
4. php artisan key:generate
5. php artisan migrate
6. php artisan passport:install

## testing
1. configure your database credentials for `mysql_testing` connection'
2. `php artisan migrate --database=mysql_testing`
3. `vendor/bin/phpunit`

### detailed testing
`vendor/bin/phpunit --testdox`
