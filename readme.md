Community Center Manager
========================

Community center manager based on [Laravel](https://laravel.com/). Laravel is a web application framework with expressive, elegant syntax.

Requirements
------------

* PHP >= 7.0.0
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Mbstring PHP Extension
    * Tokenizer PHP Extension
    * XML PHP Extension
* Composer
* MySQL Database

Installation
------------

Copy `.env.example` to `.env` and adopt database credentials accordingly.

Install dependencies:

    composer install

Generate application key:

    php artisan key:generate

Create/migrate database tables:

    php artisan migrate

For more information see https://laravel.com/docs/5.5/installation

Assets
------

Compile assets (CSS/JavaScript) using npm:

    npm run prod

License
-------

This program is open-sourced software licensed under the [AGPL license](https://www.gnu.org/licenses/agpl-3.0.en.html).
