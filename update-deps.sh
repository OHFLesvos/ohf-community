#!/bin/bash

SAIL='vendor/bin/sail'

$SAIL composer update && \
    $SAIL npm update && \
    $SAIL npm run build && \
    $SAIL pint && \
    $SAIL php vendor/bin/phpstan analyze && \
    $SAIL test
