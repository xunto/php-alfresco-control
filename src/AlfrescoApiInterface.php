<?php
namespace AlfrescoControl;

interface AlfrescoApiInterface
{
    public function request($uri, $data = [], $method = 'GET');
}