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
    public static function mockResponse($code, $data) {
        return new Response($code, [], Psr7\stream_for(json_encode($data)));
    }

    public static function mockServerVersionResponse()
    {
        return self::mockResponse(200, [
            'data' => [
                'edition' => '%edition%',
                'version' => '%version%',
                'schema' => '%schema%'
            ]
        ]);
    }

    public static function mockErrorResponse($code)
    {
        return self::mockResponse($code, [
            'error' => [
                "statusCode" => $code,
                'stackTrace' => '%stack trace%',
                "briefSummary" => "Error message",
            ]
        ]);
    }

    public function test()
    {
        $this->expectOutputString('');
        $responses = [];
        $responses[] = self::mockServerVersionResponse();
        $responses[] = self::mockResponse(200, ['data' => 'test']);
        $responses[] = self::mockErrorResponse(404);
        $responses[] = self::mockErrorResponse(500);

        $handler = new MockHandler($responses);

        $api = new GuzzleAlfrescoApi([
            'host' => 'test.com',
            'login' => 'test',
            'password' => 'test',
            'handler' => $handler
        ]);

        $result = $api->request('process_info', [
            'id' => 'test'
        ]);
        $this->assertEquals($result['data'], 'test');

        $this->expectException(AlfrescoException::class);
        $api->request('process_info', [
            'id' => 'test'
        ]);

        $this->expectException(AlfrescoException::class);
        $api->request('process_info', [
            'id' => 'test'
        ]);

        $id = '%id%';
        $data = [
            'id' => $id
        ];
        $uri = $api->resolve('process_info', $data);
        $this->assertTrue(substr_count($id, $uri) > 1);
        $this->assertTrue(empty($data['id']));
    }
}