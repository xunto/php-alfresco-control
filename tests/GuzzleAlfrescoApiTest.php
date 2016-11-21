<?php
namespace AlfrescoControl\Tests;

use AlfrescoControl\AlfrescoException;
use AlfrescoControl\GuzzleAlfrescoApi;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class GuzzleAlfrescoApiTest extends TestCase
{
    private function buildErrorResponse($code)
    {
        return new Response($code, [], Psr7\stream_for(json_encode([
            'error' => [
                "statusCode" => $code,
                'stackTrace' => '%stack trace%',
                "briefSummary" => "Error message",
            ]
        ])));
    }

    public function test()
    {
        $responses = [];

        $body = Psr7\stream_for(json_encode([
            'data' => 'test'
        ]));
        $responses[] = new Response(200, [], $body);

        $responses[] = $this->buildErrorResponse(404);
        $responses[] = $this->buildErrorResponse(500);

        $handler = new MockHandler($responses);

        $api = new GuzzleAlfrescoApi([
            'host' => 'test.com',
            'login' => 'test',
            'password' => 'test',
            'handler' => $handler
        ]);

        $result = $api->request('/test1/');
        $this->assertEquals($result['data'], 'test');

        $this->expectException(AlfrescoException::class);
        $api->request('/404/');

        $this->expectException(AlfrescoException::class);
        $api->request('/500/');

    }
}