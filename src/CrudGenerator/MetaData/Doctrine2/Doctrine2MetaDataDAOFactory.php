<?php

namespace CrudGenerator\MetaData\Doctrine2;

use CrudGenerator\MetaData\MetaDataDAO;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;

/**
 * @CodeGenerator\Environnement ZendFramework2Environnement
 */
class Doctrine2MetaDataDAOFactory
{
    private function __construct()
    {

    }

    /**
     * @return \CrudGenerator\MetaData\MetaDataDAO
     */
    public static function getInstance()
    {
        $sm = ZendFramework2Environnement::getDependence();
        $em = $sm->get('doctrine.entitymanager.orm_default');

        return new Doctrine2MetaDataDAO($em);
    }
}
