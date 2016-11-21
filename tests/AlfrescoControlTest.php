<?php
namespace AlfrescoControl\Tests;

use AlfrescoControl\Alfresco;
use AlfrescoControl\AlfrescoApiInterface;
use AlfrescoControl\GuzzleAlfrescoApi;
use AlfrescoControl\Workflow\WorkflowManager;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class AlfrescoControlTest extends TestCase
{
    public function test()
    {
        $handler = new MockHandler([
            GuzzleAlfrescoApiTest::mockServerVersionResponse()
        ]);

        $alfresco = Alfresco::create('guzzle', [
            'host' => 'test.com',
            'login' => 'test',
            'password' => 'test',
            'handler' => $handler
        ]);

        $this->assertTrue($alfresco->getApi() instanceof GuzzleAlfrescoApi);
        $this->assertTrue($alfresco->getWorkflowManager() instanceof WorkflowManager);
    }
}