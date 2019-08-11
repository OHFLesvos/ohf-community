<?php

namespace Modules\Accounting\Support\Webling;

use Webling\API\Client as WeblingApiClient;

class WeblingClient
{
    private $api;
    private $url;

    public function __construct(?string $url, ?string $apiKey)
    {
        if (empty($url)) {
            throw new \Exception('Webling API URL not defined! Please set the env variable WEBLING_API_URL.');
        }
        if (empty($apiKey)) {
            throw new \Exception('Webling API key not defined! Please set the env variable WEBLING_API_KEY.');
        }
        $this->api = new WeblingApiClient($url, $apiKey);
        $this->url = $url;
    }

    public function api()
    {
        return $this->api;
    }

    public function createUrl($suffix)
    {
        return $this->url . $suffix;
    }
}
