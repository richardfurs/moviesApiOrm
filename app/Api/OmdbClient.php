<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class OmdbClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * OmdbClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://www.omdbapi.com',
            'timeout'  => 30,
        ]);
    }

    /**
     * @param $title
     * @return Response
     */
    public function searchByTitle($title) {
        $apiKey = env('API_KEY');

        try {
            $response = $this->client->request('GET', '', [
                'query' => ['apiKey' => $apiKey, 't' => $title]
            ]);
        } catch (ClientException $e) {
            return new Response($e->getResponse());
        }

        return new Response($response);
    }
}

