<?php
namespace AlfrescoControl\Workflow;

use AlfrescoControl\AlfrescoApiInterface;

class WorkflowManager
{
    private $api;

    public function __construct(AlfrescoApiInterface $api)
    {
        $this->api = $api;
    }

    /**
     * Create new alfresco process
     * @var $process_definition_key - process key (alfresco)
     * @var $variables - additional variables (hash map)
     * @var $items - references to alfresco documents
     * @return Process process object
     **/
    public function createProcess($process_definition_key, $variables = [], $items = [])
    {
        $data = [];
        $data['processDefinitionKey'] = $process_definition_key;
        $data['businessKey'] = ($process_definition_key . uniqid());

        $variables['bpm_sendEMailNotifications'] = true;

        $data['variables'] = $variables;
        $data['items'] = $items;

        $result = $this->api->request('process_create', $data);

        return $this->findProcess($result['entry']['id']);
    }

    /**
     * Find alfresco process by id
     * @var $id - process id (alfresco)
     * @return Process process object
     **/
    public function findProcess($id)
    {
        $process = new Process();

        // Fetch basic process info
        $data = $this->api->request('process_info', [
            'id' => $id
        ]);

        $data = $data['entry'];

        $process->setId($id);
        $process->setProcessDefinitionKey($data['processDefinitionKey']);
        $process->setBusinessKey($data['businessKey']);
        $process->setCompleted($data['completed']);

        // Fetch process variables
        $data = $this->api->request('process_variables', [
            'id' => $id
        ]);

        $variables = [];
        foreach ($data['list']['entries'] as $entry) {
            $name = $entry['entry']['name'];
            @$value = $entry['entry']['value'];

            $object[$name] = $value;
        }

        $process->setVariables($variables);

        // Fetch tasks
        $process->setTasks([]);

        return $process;
    }
}
