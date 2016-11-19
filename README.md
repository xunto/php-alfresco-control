Alfresco-Control
=============

[![Coverage Status](https://coveralls.io/repos/github/xunto/alfresco-control/badge.svg?branch=master)](https://coveralls.io/github/xunto/alfresco-control?branch=master)

##Description
Simple alfresco api wrapper created for personal use. Only process creating and fetching
are currently supported. I do not guarantee further development but I accept pull requests.

##Requirements
 - php: >=5.6.0,
 - guzzle: ^6.2

##Installation
```
composer require xunto/alfresco-control
```

##Usage
```
$alfresco = new AlfrescoControl('%host%', '%login%', '%password');
$processManager = $alfresco->getProcessManager();
        
$process = $processManager->createProcess('process_definition_key', '%variables:array%', '%items:array%');
$id = $process->getId();
        
$process = $processManager->findProcess($id);
$process->getId();
```
