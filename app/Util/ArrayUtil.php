<?php

namespace App\Util;

class ArrayUtil
{
    /**
     * Checks if the array contains non-blank values specified by the given keys.
     *
     * @param  array  $array the array to be checked
     * @param  array  $keys the keys to be considered
     * @return bool if all values indicated by the specified keys are not blank
     */
    public static function elementsNotBlank(array $array, array $keys): bool
    {
        foreach ($keys as $key) {
            if (! filled($array[$key])) {
                return false;
            }
        }

        return true;
    }
}
