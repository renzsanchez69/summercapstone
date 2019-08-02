# Niugini-Hub &middot; [![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE)
A Simple E-Commerce Application for the People of Papua New Guinea

## Requirements

* PHP (7.3.1)
* Composer (1.8.3)
* PostgreSQL (10.6)

## Installation

Run `composer install` to install dependencies locally  
Run `php artisan key:generate --show` to generate a key  
Rename .env.example to .env and add the newly generated key  
Create niugini_hub database in PostgreSQL
Edit .env DB with below:  
```
DB_CONNECTION=pgsql  
DB_HOST=127.0.0.1  
DB_PORT=5432  
DB_DATABASE=niugini_hub  
DB_USERNAME=postgres  
DB_PASSWORD=*password*  
```
Edit php.ini with below:  
```
extension=pdo_pgsql  
extension=pgsql  
```
Run `php artisan migrate` to run the migrations  
Run `php artisan serve` to run the webapp

## Notes
Run `php artisan migrate:fresh --seed` for fresh migrations and seeds

### License

Niugini-Hub is [MIT licensed](./LICENSE).
