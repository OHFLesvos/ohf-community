#!/bin/bash

set -e

SAIL='vendor/bin/sail'

$SAIL composer update
$SAIL npm update
$SAIL php artisan vue-i18n:generat
$SAIL php artisan ziggy:generate
$SAIL npm run build
$SAIL pint
$SAIL php vendor/bin/phpstan analyze
# $SAIL test
