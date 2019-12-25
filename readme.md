Community Center Manager
========================

Community center manager based on [Laravel](https://laravel.com/). Laravel is a web application framework with expressive, elegant syntax.

Requirements
------------

* PHP >= 7.3.0
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

Run tests:

    ./vendor/bin/phpunit

Create assets (development)

    npm install && npm run dev
    for d in Modules/*; do (cd $d && npm install && npm run dev); done

For more information see https://laravel.com/docs/installation

Assets
------

Compile assets (CSS/JavaScript) using npm:

    npm install && npm run prod
    for d in Modules/*; do (cd $d && npm install && npm run prod); done

Development notes
-----------------

The following section assumes you are using Xampp on Windows and your custom virtualhost is `ohf.test`.
In this example, Xampp is located at `c:\devel\xampp`, and the document root is located at `C:\devel\web`.

The configuration of the file `C:\devel\xampp\apache\conf\extra\httpd-vhosts.conf` should look as follows:

    <VirtualHost *:80>
        DocumentRoot "C:/devel/web/ohf.test/public"
        ServerName ohf.test
    </VirtualHost>

    <VirtualHost *:443>
        DocumentRoot "C:/devel/web/ohf.test/public"
        ServerName ohf.test
        SSLEngine on
        SSLCertificateFile "conf/ssl.crt/ohf.test.crt"
        SSLCertificateKeyFile "conf/ssl.key/ohf.test.key"
    </VirtualHost>

The following commands creates a custom self-signed TLS certificate:

    cd c:\devel\xampp\apache
    bin\openssl.exe req -newkey rsa:2048 -sha256 -nodes -keyout conf\ssl.key\ohf.test.key -x509 -days 365 -out conf\ssl.crt\ohf.test.crt -config conf\openssl.cnf

License
-------

This program is open-sourced software licensed under the [AGPL license](https://www.gnu.org/licenses/agpl-3.0.en.html).
