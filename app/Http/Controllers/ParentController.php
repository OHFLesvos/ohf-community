<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 19.11.2017
 * Time: 13:12
 */

namespace App\Http\Controllers;


class ParentController extends Controller
{
    protected function resourceAbilityMap()
    {
        // Ensure method index is automatically authorized using policy method 'list' if authorizeResource() is used
        return array_merge(parent::resourceAbilityMap(), ['index' => 'list']);
    }
}