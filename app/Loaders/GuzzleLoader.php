<?php
namespace Hackaton\ImageStorage\Loaders;

use GuzzleHttp\Client;

class GuzzleLoader implements ILoader
{
    public function load($url)
    {
        $client = new Client(['verify' => false]);
        $response = $client->get($url);

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        return $response->getBody()->getContents();
    }
}
