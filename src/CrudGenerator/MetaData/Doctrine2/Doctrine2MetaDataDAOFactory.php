<?php

namespace CrudGenerator\MetaData\Doctrine2;

use CrudGenerator\MetaData\MetaDataDAO;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;

/**
 * @CodeGenerator\Environnement ZendFramework2Environnement
 * @CodeGenerator\Description Doctrine2
 */
class Doctrine2MetaDataDAOFactory
{
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
