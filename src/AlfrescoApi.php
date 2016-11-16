<?php
namespace AlfrescoControl;

use GuzzleHttp\Client;

class AlfrescoApi
{
    private $client;

    public function __construct($host, $login, $password, $https = true)
    {
        $base_uri = sprintf('%s://%s/alfresco/api/-default-/public/', ($https ? "https" : "http"), $host);

        $this->client = new Client([
            'base_uri' => $base_uri,
            'auth' => [$login, $password],
            'debug' => true,
            'http_errors' => false
        ]);
    }

    public function request($uri, $data = [], $method = 'GET')
    {
        $response = $this->client->request($method, $uri, [
            'json' => $data
        ]);

        $result = json_decode($response->getBody(), true);

        $code = $response->getStatusCode();
        if (($code >= 400) && ($code <= 599)) {
            throw new AlfrescoException($result['error']['briefSummary'], $code);
        }

        return $result;
    }
}