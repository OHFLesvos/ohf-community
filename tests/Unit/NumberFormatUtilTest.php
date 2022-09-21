<?php

use App\Util\NumberFormatUtil;

test('100 is 100 B', function () {
    expect(NumberFormatUtil::bytesToHuman(100))->toBe('100 B');
});

test('999 is 999 B', function () {
    expect(NumberFormatUtil::bytesToHuman(999))->toBe('999 B');
});

test('1000 is 1000 B', function () {
    expect(NumberFormatUtil::bytesToHuman(1000))->toBe('1000 B');
});

test('1024 is 1 KB', function () {
    expect(NumberFormatUtil::bytesToHuman(1024))->toBe('1 KB');
});

test('1048 is 1.02 KB', function () {
    expect(NumberFormatUtil::bytesToHuman(1048))->toBe('1.02 KB');
});

test('126976 is 1 MB', function () {
    expect(NumberFormatUtil::bytesToHuman(1024 * 1024))->toBe('1 MB');
});

test('253952 is 2 MB', function () {
    expect(NumberFormatUtil::bytesToHuman(2 * 1024 * 1024))->toBe('2 MB');
});

test('1073741824 is 1 GB', function () {
    expect(NumberFormatUtil::bytesToHuman(1024 * 1024 * 1024))->toBe('1 GB');
});

test('1610612736 1.5 GB', function () {
    expect(NumberFormatUtil::bytesToHuman(1.5 * 1024 * 1024 * 1024))->toBe('1.5 GB');
});
