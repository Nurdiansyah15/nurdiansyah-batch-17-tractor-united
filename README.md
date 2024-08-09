# Readme Please
## Requirements
- Laravel 9.0
- PHP 8.0
- Composer
- Postman
- JWT Provider by [Tymon](https://github.com/tymon/jwt-auth)
- SQL Database

## How to startup
- First you must setting `.env` file.
- Match database credentials with your own.
- Run commands: `composer install`.
- Run commands: `php artisan key:generate` for generate app key.
- Run commands: `php artisan migrate` for run migration.
- Run commands: `php artisan db:seed` for run seed.
- Run commands: `php artisan jwt:secret` for generate jwt secret key.

## Postman Documentation
- [Postman Collection](https://documenter.getpostman.com/view/33287012/2sA3s3GWG5)
