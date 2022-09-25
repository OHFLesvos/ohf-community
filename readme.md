# Community Center Manager

Community center manager web application based on [Laravel](https://laravel.com/) PHP MVC framework with a VueJS-based frontend.

## License

This program is open-sourced software licensed under the [AGPL license](https://www.gnu.org/licenses/agpl-3.0.en.html).

## Requirements

* [PHP](https://www.php.net/) >= 8.1
  * OpenSSL PHP Extension
  * PDO PHP Extension
  * Mbstring PHP Extension
  * Tokenizer PHP Extension
  * XML PHP Extension
  * Intl Extension
  * Imagick Extension (ImageMagick)
* [PHP Composer](https://getcomposer.org/)
* MySQL/MariaDB/PostgreSQL Database
* [Node.js](https://nodejs.org/en/) 16.x

### Optional 3rd-party services

* [Google OAuth](https://developers.google.com/identity/protocols/oauth2/web-server) for login using a OAuth service provider
* [Sentry](https://sentry.io/welcome/) for application monitoring
* [Symfony Mailer](https://symfony.com/doc/current/mailer.html)-compatible mail service
* [Monolog](https://github.com/Seldaek/monolog)-compatible logging service for log collection and analysis

## Installation

Copy `.env.example` to `.env` and adapt database credentials and other settings accordingly.

Install PHP dependencies:

    composer install

Generate application key:

    php artisan key:generate

Create symlink for public storage disk:

    php artisan storage:link

Create/migrate database tables:

    php artisan migrate

You have to compile the frontend assets (see below) in order for Javascript and CSS files to be available in the public web folder using `npm run prod` or `npm run dev` (for development).

Configure the desired virtual host / domain your webserver to point to the `public` directory.

For more information see https://laravel.com/docs/installation

## Login with Google OAuth

Obtain OAuth 2.0 credentials from the Google API Console. For more info see https://developers.google.com/identity/protocols/oauth2

In your `.env` file, set the values of the following variables according to the information from Google.

* GOOGLE_CLIENT_ID
* GOOGLE_CLIENT_SECRET
* GOOGLE_REDIRECT
* GOOGLE_ORGANIZATION_DOMAIN (optional)

Once set, a new "Google Login" button should appear on the login view.

## Testing

Run tests:

    php artisan test

## Assets

Create assets (CSS/JavaScript) using npm:

    npm install
    php artisan vue-i18n:generate
    php artisan ziggy:generate
    npm run dev

## Frontend 3rd party libraries

The following list provides links to the documentation pages of the most common frontend libraries used in this project:

* [Bootstrap 4](https://getbootstrap.com/docs/4.6/getting-started/introduction/)
* [VueJS 2](https://v2.vuejs.org/v2/guide/)
* [Bootstrap Vue](https://bootstrap-vue.org/docs)
* [FontAwesome 6](https://fontawesome.com/v6/search)
* [Chart.js 2](https://www.chartjs.org/docs/2.9.4/)
* [Moment.js](https://momentjs.com/docs/)
* [Ziggy](https://github.com/tighten/ziggy)
* [VeeValidate](https://vee-validate.logaretm.com/v2/guide/)

## Releasing

* Ensure tests, code style and static code analysis on `master` branch run without errors
* Update `Changelog.md` file
* Commit into Git
* Merge `master` branch into `production` branch.
* On the `production` branch, create Git tag of the form `vX.Y.Z`, where X is the major version, Y is the minor version and Z is the patch version. Example: , e.g `v1.0.0`
* Push Git changes, including tags, to origin repository

## Deployment onto production server

It is recommended to execute a production deployment / upgrade as follows:

    php artisan down --retry=60
    php composer install --optimize-autoloader --no-dev
    php artisan optimize
    php artisan migrate --force
    php artisan up

## Code style fixer

Run:

   ./vendor/bin/pint

or when using Laravel Sail:

    sail pint

More information here: https://github.com/laravel/pint

## Static code analysis

Run:

    ./vendor/bin/phpstan analyse

or when using Laravel Sail:

    sail php ./vendor/bin/phpstan analyse

More information here: https://github.com/nunomaduro/larastan

## Development notes

The following section assumes you are using Xampp on Windows and your custom virtualhost is `ohf.test`.
In this example, Xampp is located at `c:\devel\xampp`, and the document root is located at `C:\devel\web`.

The configuration of the file `C:\devel\xampp\apache\conf\extra\httpd-vhosts.conf` should look as follows:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/devel/web/ohf.test/public"
    ServerName ohf.test
    <Directory "C:/devel/web/ohf.test/public">
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:443>
    DocumentRoot "C:/devel/web/ohf.test/public"
    ServerName ohf.test
    SSLEngine on
    SSLCertificateFile "conf/ssl.crt/ohf.test.crt"
    SSLCertificateKeyFile "conf/ssl.key/ohf.test.key"
    <Directory "C:/devel/web/ohf.test/public">
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>
</VirtualHost>
```

The following commands create a custom self-signed TLS certificate:

    cd c:\devel\xampp\apache
    bin\openssl.exe req -newkey rsa:2048 -sha256 -nodes -keyout conf\ssl.key\ohf.test.key -x509 -days 365 -out conf\ssl.crt\ohf.test.crt -config conf\openssl.cnf

## Development using Laravel Sail (Docker)

[Laravel Sail](https://laravel.com/docs/9.x/sail) is a light-weight command-line interface for interacting with Laravel's default Docker development environment. 

Run the following command to install the composer dependencies:

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Configure a Shell alias for sail:

```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

Copy the `.env.example` file to `.env` and set `DB_HOST=mysql`

Start the application:

    sail up -d

Create app key and run migrations

    sail artisan key:generate
    sail artisan migrate
    sail artisan storage:link

Install NPM packages and build javascript assets

    sail npm install
    sail npm run dev

Access the databa

Stop the application:

    sail down
