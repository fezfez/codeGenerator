<?php

namespace CrudGenerator\MetaData\Doctrine2;

use CrudGenerator\MetaData\MetaDataDAO;
use Zend\ServiceManager\ServiceManager;

class Doctrine2MetaDataDAOFactory
{
    private function __construct()
    {

    }

    /**
     * @param ServiceManager $sm
     * @return \CrudGenerator\MetaData\MetaDataDAO
     */
    public static function getInstance(ServiceManager $sm)
    {
        $em = $sm->get('doctrine.entitymanager.orm_default');
        return new Doctrine2MetaDataDAO($em);
    }
}
