<?php

namespace App\Support\Accounting\Webling;

use Webling\API\Client;
use Webling\CacheAdapters\FileCacheAdapter;
use Webling\Cache\Cache;

class WeblingClient
{
    private $url;
    private $client;
    private $cache;

    public function __construct(?string $url, ?string $apiKey)
    {
        if (empty($url)) {
            throw new \Exception('Webling API URL not defined! Please set the env variable WEBLING_API_URL.');
        }
        $this->url = $url;
        if (empty($apiKey)) {
            throw new \Exception('Webling API key not defined! Please set the env variable WEBLING_API_KEY.');
        }

        $this->client = new Client($url, $apiKey);
        $adapter = new FileCacheAdapter([
            'directory' => storage_path('webling_cache'),
        ]);
        $this->cache = new Cache($this->client, $adapter);
    }

    public function createUrl($suffix)
    {
        return $this->url . $suffix;
    }

    /**
     * List objects
     */
    public function listObjectIds(string $name, bool $cached = true)
    {
        $data = null;
        if ($cached) {
            $data = $this->cache->getRoot($name);
        } else {
            $response = $this->client->get($name);
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $data = $response->getData();
            }
        }
        if ($data != null && isset($data['objects'])) {
            return $data['objects'];
        }
        // TODO error handling
        return null;
    }

    public function listObjectsUncached(string $name, bool $full = false, string $filter = null)
    {
        $params = [];
        if ($full) {
            $params['format'] = 'full';
        }
        if ($filter) {
            $params['filter'] = $filter;
        }
        $response = $this->client->get($name . '?' . http_build_query($params));
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $response->getData();
        }
        // TODO error handling
        return null;
    }

    /**
     * Get single object
     */
    public function getObject(string $name, int $id, bool $cached = true)
    {
        if ($cached) {
            return $this->cache->getObject($name, $id);
        }
        $response = $this->client->get($name . '/' . $id);
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $data = $response->getData();
            // the single response does not have the id attribute, set it here for consistency
            $data['id'] = $objectId;
            return $data;
        } else {
            // TODO error handling
            return null;
        }
    }

    /**
     * Get multiple objects
     */
    public function getObjects(string $name, array $ids, bool $cached = true)
    {
        if (count($ids) == 1) {
            return [$this->getObject($name, $ids[0], $cached)];
        }
        if ($cached) {
            return $this->cache->getObjects($name, $ids);
        }
        $data = [];
        $chunk_size = 250;
        for ($offset = 0; $offset < count($ids); $offset += $chunk_size) {
            $ids_slice = array_slice($ids, $offset, $chunk_size);
            $response = $this->client->get($name  . '/' . implode(',', $ids_slice));
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $data = array_merge($data, $response->getData());
            }
            // TODO error handling
        }
        return $data;
    }

    /**
     * Store object
     */
    public function storeObject(string $name, array $data)
    {
        $response = $this->client->post($name, $data);
        if ($response->getStatusCode() < 400) {
            return $response->getData();
        }
        throw new \Exception('Server responded with code ' . $response->getStatusCode().': ' . $response->getRawData());
    }

    /**
     * Check for updates and renew cache
     */
    public function updateCache()
    {
        $this->cache->updateCache();
    }

    /**
     * Clear the whole cache
     */
    public function clearCache()
    {
        $this->cache->clearCache();
    }

}
