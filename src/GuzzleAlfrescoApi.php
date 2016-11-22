<?php
namespace AlfrescoControl;

use GuzzleHttp\Client;

class GuzzleAlfrescoApi implements AlfrescoApiInterface
{
    private $client;

    private $edition;
    private $version;
    private $schema;

    public static $routes = [
        'server_version' => [
            'uri' => 'service/api/server/',
            'method' => 'GET'
        ],
        'process_create' => [
            'uri' => 'api/-default-/public/workflow/versions/1/processes',
            'method' => 'POST'
        ],
        'process_info' => [
            'uri' => 'api/-default-/public/workflow/versions/1/processes/{id}',
            'method' => 'GET'
        ],
        'process_variables' => [
            'uri' => 'api/-default-/public/workflow/versions/1/processes/{id}/variables',
            'method' => 'GET'
        ],
        'process_tasks' => [
            'uri' => 'api/-default-/public/workflow/versions/1/processes/{id}/tasks',
            'method' => 'GET'
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

    public function resolve($route, &$data)
    {
        $definition = @self::$routes[$route];

        if (empty($definition)) throw new \InvalidArgumentException("No route: $route");

        $parts = preg_split("/[{}]/", $definition['uri']);

        $definition['uri'] = "";
        $switch = false;
        foreach ($parts as $part) {
            if ($switch) {
                $offset = $part;

                if (empty($data[$offset])) {
                    throw new \InvalidArgumentException("$offset is no defined");
                }

                $part = $data[$offset];
                unset($data[$offset]);
            }

            $definition['uri'] .= $part;
            $switch = !$switch;
        }

        return $definition;
    }

    public function request($action, $data = [])
    {
        $route = $this->resolve($action, $data);

        $response = $this->client->request($route['method'], $route['uri'], [
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
