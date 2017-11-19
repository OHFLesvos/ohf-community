<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 19.11.2017
 * Time: 16:29
 */

namespace App\Util;


class ExtendedCountries extends \Countries
{
    public static function getList($locale) {
        $countries = parent::getList($locale);

        $countries['krg'] = 'Iraqi Kurdistan';

        asort($countries);
        return $countries;
    }

}