<?php
namespace AlfrescoControl;

use GuzzleHttp\Client;

class GuzzleAlfrescoApi implements AlfrescoApiInterface
{
    private $client;

    private $edition;
    private $version;
    private $schema;

    private $routes = [
        'server_version' => [
            'uri' => 'service/api/server/',
            'method' => 'GET'
        ],
        'process_create' => [
            'uri' => 'api/{api_id}/public/workflow/versions/1/processes',
            'method' => 'POST'
        ],
        'process_info' => [
            'uri' => 'api/{api_id}/public/workflow/versions/1/processes/{id}',
            'method' => 'POST'
        ],
        'process_variables' => [
            'uri' => 'api/{api_id}/public/workflow/versions/1/processes/{id}/variables',
            'method' => 'POST'
        ],
        'process_tasks' => [
            'uri' => 'api/{api_id}/public/workflow/versions/1/processes/{id}/tasks',
            'method' => 'POST'
        ]
    ];

    public function __construct($config = [])
    {
        $https = (@$config['https'] === true) ? true : false;

        $host = @$config['host'];
        $login = @$config['login'];
        $password = @$config['password'];
        $handler = @$config['handler'];

        $base_uri = sprintf('%s://%s/alfresco/', ($https ? "https" : "http"), $host);

        $this->client = new Client([
            'base_uri' => $base_uri,
            'auth' => [$login, $password],
            'handler' => $handler,
            'http_errors' => false
        ]);

        $result = $this->request('server_version');
        $this->edition = @$result['data']['edition'];
        $this->version = @$result['data']['version'];
        $this->schema = @$result['data']['schema'];
    }

    public function request($action, $data = [])
    {
        $route_definition = @$this->routes[$action];

        if (empty($route_definition)) throw new \InvalidArgumentException("No action $action");

        $uri = $route_definition['uri'];
        $method = $route_definition['method'];

//      Find and set url arguments
        $uri = str_replace('{api_id}', '-default-', $uri);

        $uri_arguments = [];
        preg_match_all('/\{(.*?)\}/', $uri, $uri_arguments);
        $uri_arguments = $uri_arguments[1];

        foreach ($uri_arguments as $argument) {
            $uri = str_replace(sprintf('{%s}', $argument), $data[$argument], $uri);
            unset($data[$argument]);
        }

//      Send request
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