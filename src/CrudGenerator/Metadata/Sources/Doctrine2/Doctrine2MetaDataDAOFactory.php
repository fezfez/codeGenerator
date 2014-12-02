<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\Doctrine2;

use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\MetaDataDAOSimpleFactoryInterface;
use Doctrine\ORM\EntityManager;

/**
 * Doctrine2 Metadata DAO in Zend Framework 2 Environnement
 *
 * @author Stéphane Demonchaux
 */
class Doctrine2MetaDataDAOFactory implements MetaDataDAOSimpleFactoryInterface
{
    /**
     * Create Doctrine2MetaDataDAO instance
     * @return Doctrine2MetaDataDAO
     */
    public static function getInstance()
    {
        $fileManager    = new FileManager();
        $serviceManager = ZendFramework2Environnement::getDependence($fileManager);
        $entityManager  = $serviceManager->get('doctrine.entitymanager.orm_default');

        if (($entityManager instanceof EntityManager) === false) {
            throw new \Exception(
                sprintf(
                    'Service manager return instanceof "%s" instead of "%s"',
                    is_object($entityManager) ? get_class($entityManager) : gettype($entityManager),
                    'Doctrine\ORM\EntityManager'
                )
            );
        }

        return new Doctrine2MetaDataDAO($entityManager);
    }

    /**
     * @param  MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource)
    {
        try {
            ZendFramework2Environnement::getDependence(new FileManager());

            return true;
        } catch (EnvironnementResolverException $e) {
            $metadataSource->setFalseDependencie($e->getMessage());

            return false;
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Metadata\Sources\MetaDataDAO::getDataObject()
    */
    public static function getDescription()
    {
        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Doctrine2")
                   ->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory')
                   ->setMetadataDao('CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAO');

        return $dataObject;
    }
}
