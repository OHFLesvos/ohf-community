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
        'street',
        'street_local',
        'zip',
        'city',
        'city_local',
        'province',
        'country_code',
        'country_name',
        'latlong',
        'latitude',
        'longitude',
        'google_places_id',
        'description',
    ];

    protected $nullable = [
        'name_local',
        'street',
        'street_local',
        'zip',
        'city',
        'city_local',
        'province',
        'country_code',        
        'latitude',
        'longitude',
        'google_places_id',
        'description',
    ];

    /**
     * Get the country name based on the country code
     * 
     * @return string
     */
    public function getCountryNameAttribute() {
        if ($this->country_code != null) {
            return \Countries::getOne($this->country_code, \App::getLocale());
        }
        return null;
    }

    /**
     * Set the country code based on the country name
     *
     * @param  string  $value
     * @return void
     */
    public function setCountryNameAttribute($value)
    {
        $this->attributes['country_code'] = $value != null ? array_flip(\Countries::getList(\App::getLocale()))[$value] ?? null : null;
    }    

    public function getAddressAttribute()
    {
        return self::formatAddress($this->street, $this->city, $this->zip, $this->province, $this->country_name);
    }

    public function getAddressLocalAttribute()
    {
        return self::formatAddress($this->street_local, $this->city_local, $this->zip, $this->province, $this->country_name);
    }

    private static function formatAddress($street, $city, $zip, $province, $country) {
        $value = '';
        if (isset($street)) {
            $value.= $street;
        }
        if (isset($city)) {
            if (!empty($value)) {
                $value.=', ';
            }
            $value.= $city;
            if (isset($zip)) {
                $value.= ' ' . $zip;
            }
        }
        if (isset($province)) {
            if (!empty($value)) {
                $value.=', ';
            }
            $value.= $province;
        }
        if (isset($country)) {
            if (!empty($value)) {
                $value.=', ';
            }
            $value.= $country;
        }
        return !empty($value) ? $value : null;
    }

    public function getMapsLocationAttribute() {
        if (isset($this->google_places_id)) {
            return 'place_id:' . $this->google_places_id;
        }
        if (isset($this->latitude) && isset($this->longitude)) {
            return $this->latitude . ',' . $this->longitude;
        }
        return $this->address;
    }

}
