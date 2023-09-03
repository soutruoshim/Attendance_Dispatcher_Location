<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## About This Project

<!-- <img src="https://raw.githubusercontent.com/soutruoshim/hr-app-laravel-10/main/screenshot/s1.png">
<img src="https://raw.githubusercontent.com/soutruoshim/hr-app-laravel-10/main/screenshot/s2.png"> -->
## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Setup Tool
- **[XAMPP](https://www.apachefriends.org/)**
- **[COMPOSER](https://getcomposer.org/download/)**


## Setup Project
### step 1
<code>
git clone https://github.com/soutruoshim/Attendance_Dispatcher_Location.git
cd Attendance_Dispatcher_Location
composer install or composer update
</code>

### step 2
create database a local database phpmyadmin. 
rename (.env.example) to (.env).

<b>set connection .env</b>
<code>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_name
DB_USERNAME=root
DB_PASSWORD=
</code>
### step 3
<code>php artisan migrate</code>
<code>php artisan passport:install</code>
<code>php artisan key:generate</code>
<code>php artisan storage:link</code>
<code>php artisan db:seed</code>
<code>composer dump-autoload</code>

### step 4
<code>php artisan serve</code>


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# Attendance Dispatcher Location