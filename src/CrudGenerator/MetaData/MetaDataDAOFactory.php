<?php

namespace CrudGenerator\MetaData;

use CrudGenerator\MetaData\DAO\MetaData;
use Zend\ServiceManager\ServiceManager;

class MetaDataDAOFactory
{
    private function __construct()
    {
        
    }
    
    public static function getInstance(ServiceManager $sm)
    {
        $em = $sm->get('doctrine.entitymanager.orm_default');
        return new MetaData($em);
    }
}
