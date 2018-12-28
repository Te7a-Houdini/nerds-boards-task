<?php

namespace App\StackOverflow;

use GuzzleHttp\Client;

abstract class BaseApi
{
    public $client;
    public $response;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.stackexchange.com/2.2/',
            'query'   => ['site' => 'stackoverflow'],
        ]);
    }

    public function get($params  = [])
    {
        $response = $this->call($params);

        if ($response->getStatusCode() == 200) {
            return collect(json_decode($response->getBody()->getContents())->items);
        }
        
        return null;
    }

    abstract protected function call($params = []);
}
