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
    private $variablesReference;
    private $tasksReference;

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
        if (!isset($this->variables)) {
            $reference = $this->variablesReference;
            $this->variables = $reference();
        }

        return $this->variables;
    }

    public function getTasks()
    {
        if (!isset($this->tasks)) {
            $reference = $this->tasksReference;
            $this->tasks = $reference();
        }

        return $this->tasks;
    }

    public function setVariablesReference(Closure $variablesReference)
    {
        $this->variablesReference = $variablesReference;
    }

    public function setTasksReference(Closure $tasksReference)
    {
        $this->tasksReference = $tasksReference;
    }

}