#!/bin/bash

TAG=${1:-latest}
TARGETDIR=${2:-~/}

PHP=php74

if [ ! -d $TARGETDIR ]; then
        echo "Target directory $TARGETDIR not found!"
        exit 1
fi

TMPDIR=$(mktemp -d)
cd $TMPDIR
wget -nv -P $TMPDIR https://github.com/OHFLesvos/ohf-community/releases/download/$TAG/dist.zip

if [ ! -f $TMPDIR/dist.zip ]; then
        echo "No file found for tag $TAG!"
        rm -r $TMPDIR
        exit 1
fi

set -e

mkdir $TMPDIR/dist
unzip -q $TMPDIR/dist.zip -d $TMPDIR/dist

cd $TARGETDIR
$PHP artisan down --retry=60
rsync -a --delete --exclude='.env' --exclude='storage/*' $TMPDIR/dist/ $TARGETDIR
php artisan optimize
php artisan storage:link
$PHP artisan migrate --force
$PHP artisan up

rm -r $TMPDIR
