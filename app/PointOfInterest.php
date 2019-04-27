<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

/**
 * Point of interest
 */
class PointOfInterest extends Model
{
    use NullableFields;

    protected $table = 'points_of_interest';

    protected $fillable = [
        'name',
        'name_local',
        'address',
        'address_local',
        'latlong',
        'latitude',
        'longitude',
        'description',
    ];

    protected $nullable = [
        'name_local',
        'address_local',
        'lat',
        'long',
        'description',
    ];

    public function getMapsLocationAttribute() {
        return "$this->name, $this->address";
    }

}
