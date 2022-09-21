<?php

use App\Util\NumberFormatUtil;

test('bytesToHuman 100 B', function () {
    expect(NumberFormatUtil::bytesToHuman(100))->toBe('100 B');
});

test('bytesToHuman 999 B', function () {
    expect(NumberFormatUtil::bytesToHuman(999))->toBe('999 B');
});

test('bytesToHuman 1000 B', function () {
    expect(NumberFormatUtil::bytesToHuman(1000))->toBe('1000 B');
});

test('bytesToHuman 1 KB', function () {
    expect(NumberFormatUtil::bytesToHuman(1024))->toBe('1 KB');
});

test('bytesToHuman 1.02 KB', function () {
    expect(NumberFormatUtil::bytesToHuman(1048))->toBe('1.02 KB');
});

test('bytesToHuman 1 MB', function () {
    expect(NumberFormatUtil::bytesToHuman(1024 * 1024))->toBe('1 MB');
});

test('bytesToHuman 2 MB', function () {
    expect(NumberFormatUtil::bytesToHuman(2 * 1024 * 1024))->toBe('2 MB');
});

test('bytesToHuman 1 GB', function () {
    expect(NumberFormatUtil::bytesToHuman(1024 * 1024 * 1024))->toBe('1 GB');
});

test('bytesToHuman 1.5 GB', function () {
    expect(NumberFormatUtil::bytesToHuman(1.5 * 1024 * 1024 * 1024))->toBe('1.5 GB');
});
