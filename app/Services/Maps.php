<?php

namespace App\Services;

use GuzzleHttp\Client;

class Maps
{
    /***
     * Geocodes an address to get the latitude and longitude
     */

    public static function geocodeAddress($address, $postalCode, $country, $name)
    {
        $url = 'https://geocode.maps.co/search?q=' . urlencode($address . ', ' . $postalCode . ', ' . $country . ', ' . $name);

        $geocodeResponse = (new Client())->get($url)->getBody();

        $geocodeData = json_decode($geocodeResponse);

        $coordinates['lat'] = null;
        $coordinates['lng'] = null;

        if (!empty($geocodeData) && isset($geocodeData[0])) {
            $coordinates['lat'] = $geocodeData[0]->lat;
            $coordinates['lng'] = $geocodeData[0]->lon;
        }

        return $coordinates;
    }
}
