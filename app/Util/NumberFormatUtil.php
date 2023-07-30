<?php

namespace App\Util;

class NumberFormatUtil
{
    public static function bytesToHuman(int $bytes): string
    {
        $i = floor(log($bytes) / log(1024));
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        return sprintf('%.02F', $bytes / pow(1024, $i)) * 1 .' '.$sizes[$i];
    }
}
