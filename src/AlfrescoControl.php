<?php
namespace AlfrescoControl;

use AlfrescoControl\Workflow\WorkflowManager;

class AlfrescoControl
{
    private $api;
    private $processManager;

    public function __construct($host, $login, $password, $config = [])
    {
        $this->api = new AlfrescoApi($host, $login, $password, $config);
        $this->processManager = new WorkflowManager($this->api);
    }

    /**
     * @return WorkflowManager
     */
    public function getProcessManager()
    {
        return $this->processManager;
    }
}