<?php
namespace AlfrescoControl\Tests;

use AlfrescoControl\AlfrescoApi;
use AlfrescoControl\Workflow\WorkflowManager;
use PHPUnit\Framework\TestCase;

class AlfrescoWorkflowTest extends TestCase
{
    private $id;
    private $process_variables;
    private $process_info;

    public function setUp()
    {
        parent::setUp();

        $this->process_info = [
            "entry" => [
                "processDefinitionId" => "test:26:48404",
                "startUserId" => "test",
                "startActivityId" => "startevent1",
                "businessKey" => "test582cd8e05d243",
                "startedAt" => new \DateTime('now'),
                "id" => "123123",
                "completed" => false,
                "processDefinitionKey" => "test"
            ]
        ];

        $this->process_variables = [
            'list' => [
                'pagination' => [
                    'count' => 31,
                    'hasMoreItems' => false,
                    'totalItems' => 31,
                    'skipCount' => 0,
                    'maxItems' => 100,
                ],
                'entries' => [
                    [
                        'entry' => [
                            "name" => "bpm_test1",
                            "type" => "d:boolean",
                            "value" => true
                        ]
                    ]
                ]
            ]
        ];
    }

    public function test()
    {
        $this->expectOutputString('');

        $api = $this->createMock(AlfrescoApi::class);
        $api->method('request')
            ->willReturnOnConsecutiveCalls(
                $this->process_info,
                $this->process_info,
                $this->process_variables
            );

        $manager = new WorkflowManager($api);

        // Creation
        $process = $manager->createProcess('test', ['test' => 'test'], ['test' => 'test']);
        $this->assertEquals($process->getId(), $this->process_info['entry']['id']);
        $this->assertEquals($process->getProcessDefinitionKey(), $this->process_info['entry']['processDefinitionKey']);
        $this->assertEquals($process->getBusinessKey(), $this->process_info['entry']['businessKey']);
        $this->assertEquals($process->getCompleted(), $this->process_info['entry']['completed']);

        // Find
        $process = $manager->findProcess($process->getId());
        $this->assertEquals($process->getId(), $this->process_info['entry']['id']);
        $this->assertEquals($process->getProcessDefinitionKey(), $this->process_info['entry']['processDefinitionKey']);
        $this->assertEquals($process->getBusinessKey(), $this->process_info['entry']['businessKey']);
        $this->assertEquals($process->getCompleted(), $this->process_info['entry']['completed']);

        $variables = $process->getVariables();
        $this->assertEquals($variables['bpm_test1'], true);

        // TODO: test after implementation
        $tasks = $process->getTasks();
        $this->assertEquals($tasks, []);
    }
}