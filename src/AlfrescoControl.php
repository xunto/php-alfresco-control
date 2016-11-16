<?php
namespace AlfrescoControl;

use AlfrescoControl\Workflow\WorkflowManager;

class AlfrescoControl
{
    private $api;
    private $processManager;

    public function __construct($host, $login, $password, $https = true)
    {
        $this->api = new AlfrescoApi($host, $login, $password, $https);
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