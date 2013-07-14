<?php

namespace CrudGenerator\MetaData\Doctrine2;

use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\FileManager;

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
        $fileManager = new FileManager();
        $sm = ZendFramework2Environnement::getDependence($fileManager);
        $em = $sm->get('doctrine.entitymanager.orm_default');

        return new Doctrine2MetaDataDAO($em);
    }
}
