<?php
namespace AlfrescoControl\Tests;

use AlfrescoControl\AlfrescoApiInterface;
use AlfrescoControl\Workflow\WorkflowManager;
use PHPUnit\Framework\TestCase;

class AlfrescoWorkflowTest extends TestCase
{
    private $process_variables;
    private $process_info;

    public function setUp()
    {
        parent::setUp();

        $this->process_info = [
            "entry" => [
                "processDefinitionId" => "%processDefinitionId%",
                "startUserId" => "%startUserId%",
                "startActivityId" => '%startActivityId%',
                "businessKey" => '%businessKey%',
                "startedAt" => '%date%',
                "id" => "%id%",
                "completed" => false,
                "processDefinitionKey" => "%processDefinitionKey%"
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
        $api = $this->createMock(AlfrescoApiInterface::class);
        $api->method('request')
            ->willReturnOnConsecutiveCalls(
                $this->process_info, // On create process
                $this->process_info, // On find process after creating
                $this->process_variables, // On find process after creating
                $this->process_info, // On find process
                $this->process_variables // On find process
            );

        $manager = new WorkflowManager($api);

        // Creation
        $process = $manager->createProcess('test', ['test' => 'test'], ['test' => 'test']);
        $this->assertEquals($process->getId(), $this->process_info['entry']['id']);
        $this->assertEquals($process->getProcessDefinitionKey(), $this->process_info['entry']['processDefinitionKey']);
        $this->assertEquals($process->getBusinessKey(), $this->process_info['entry']['businessKey']);
        $this->assertEquals($process->getCompleted(), $this->process_info['entry']['completed']);

        // Search
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