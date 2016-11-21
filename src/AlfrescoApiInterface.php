<?php
namespace AlfrescoControl;

interface AlfrescoApiInterface
{
    public function request($action, $data = []);
}