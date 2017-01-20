<?php
namespace AlfrescoControl\Workflow;

use Closure;

class Process
{
    private $id;
    private $processDefinitionKey;
    private $businessKey;
    private $completed;
    private $variables;
    private $tasks;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getProcessDefinitionKey()
    {
        return $this->processDefinitionKey;
    }

    /**
     * @param string $processDefinitionKey
     */
    public function setProcessDefinitionKey($processDefinitionKey)
    {
        $this->processDefinitionKey = $processDefinitionKey;
    }

    /**
     * @return string
     */
    public function getBusinessKey()
    {
        return $this->businessKey;
    }

    /**
     * @param string $businessKey
     */
    public function setBusinessKey($businessKey)
    {
        $this->businessKey = $businessKey;
    }

    /**
     * @return bool
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    public function getVariables()
    {
        return $this->variables;
    }

    public function setVariables($variables)
    {
        $this->variables = $variables;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }

}