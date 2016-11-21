<?php
namespace AlfrescoControl\Tests;

use AlfrescoControl\Alfresco;
use AlfrescoControl\GuzzleAlfrescoApi;
use AlfrescoControl\Workflow\WorkflowManager;
use PHPUnit\Framework\TestCase;

class AlfrescoControlTest extends TestCase
{
    public function test()
    {
        $alfresco = Alfresco::create('guzzle', [
            'host' => 'test.com',
            'login' => 'test',
            'password' => 'test'
        ]);

        $this->assertTrue($alfresco->getApi() instanceof GuzzleAlfrescoApi);
        $this->assertTrue($alfresco->getWorkflowManager() instanceof WorkflowManager);
    }
}