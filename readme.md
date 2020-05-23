Community Center Manager
========================

Community center manager based on [Laravel](https://laravel.com/). Laravel is a web application framework with expressive, elegant syntax.

Requirements
------------

* PHP >= 7.4.0
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

Create symlink for public storage disk:

    php artisan storage:link

Create/migrate database tables:

    php artisan migrate

For more information see https://laravel.com/docs/installation

Login with Google OAuth
-----------------------

Obtain OAuth 2.0 credentials from the Google API Console. For more info see https://developers.google.com/identity/protocols/oauth2

In your `.env` file, set the values of the following variables according to the information from Google.

* GOOGLE_CLIENT_ID
* GOOGLE_CLIENT_SECRET
* GOOGLE_REDIRECT

Once set, a new "Google Login" button should appear on the login view.

Testing
-------

Run tests:

    php artisan test

Run code quality checks:

    php artisan insights

Assets
------

Create assets (CSS/JavaScript) using npm:

    npm install
    php artisan vue-i18n:generate
    php artisan ziggy:generate "resources/js/ziggy.js"
    npm run dev

Releasing
---------

* Update `Changelog.md` file
* Set version number of format `major.minor.patch` in property `version` in `config/app.php` file
* Compile assets for production release: `npm install && npm run prod`
* Commit into VCS
* Create VCS tag
* Push VCS changes to origin repository

Deployment onto production server
---------------------------------

It is recommended to execute a production deployment / upgrade as follows:

    php artisan down --message="Upgrading Application, please wait..." --retry=60
    php composer install --optimize-autoloader --no-dev
    php artisan cache:clear
    php artisan view:cache
    php artisan config:cache
    php artisan route:cache
    php artisan migrate --force
    php artisan up

Development notes
-----------------

The following section assumes you are using Xampp on Windows and your custom virtualhost is `ohf.test`.
In this example, Xampp is located at `c:\devel\xampp`, and the document root is located at `C:\devel\web`.

The configuration of the file `C:\devel\xampp\apache\conf\extra\httpd-vhosts.conf` should look as follows:

    <VirtualHost ohf.test:80>
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

    <VirtualHost ohf.test:443>
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

The following commands create a custom self-signed TLS certificate:

    cd c:\devel\xampp\apache
    bin\openssl.exe req -newkey rsa:2048 -sha256 -nodes -keyout conf\ssl.key\ohf.test.key -x509 -days 365 -out conf\ssl.crt\ohf.test.crt -config conf\openssl.cnf

License
-------

This program is open-sourced software licensed under the [AGPL license](https://www.gnu.org/licenses/agpl-3.0.en.html).
