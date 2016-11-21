<?php
namespace AlfrescoControl;

use GuzzleHttp\Client;

class GuzzleAlfrescoApi implements AlfrescoApiInterface
{
    private $client;

    public function __construct($config = [])
    {
        $https = (@$config['https'] === true) ? true : false;

        $host = @$config['host'];
        $login = @$config['login'];
        $password = @$config['password'];
        $handler = @$config['handler'];

        $base_uri = sprintf('%s://%s/alfresco/api/-default-/public/', ($https ? "https" : "http"), $host);

        $this->client = new Client([
            'base_uri' => $base_uri,
            'auth' => [$login, $password],
            'handler' => $handler,
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