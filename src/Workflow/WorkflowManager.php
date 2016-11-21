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
     * @var $business_key - unique key for process
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
        return $this->buildProcessObject($result['entry']);
    }

    private function buildProcessObject($data)
    {
        $process = new Process();

        $id = $data['id'];

        $process->setId($id);
        $process->setProcessDefinitionKey($data['processDefinitionKey']);
        $process->setBusinessKey($data['businessKey']);
        $process->setCompleted($data['completed']);

        $process->setVariablesReference(function () use ($id) {
            $result = $this->api->request('process_variables', [
                'id' => $id
            ]);

            $object = [];
            foreach ($result['list']['entries'] as $entry) {
                $name = $entry['entry']['name'];
                @$value = $entry['entry']['value'];

                $object[$name] = $value;
            }

            return $object;
        });

        $process->setTasksReference(function () use ($id) {
            //TODO: implement
//            $data = $this->api->request('/workflow/versions/1/processes/'. $process->getId() .'/tasks');
            return [];
        });

        return $process;
    }

    /**
     * Find alfresco process by id
     * @var $id - process id (alfresco)
     * @return Process process object
     **/
    public function findProcess($id)
    {
        $result = $this->api->request('process_info', [
            'id' => $id
        ]);
        return $this->buildProcessObject($result['entry']);
    }
}