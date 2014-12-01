<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Sources\Oracle;

use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriver;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryConfigInterface;

/**
 * Create PDO Metadata DAO instance
 *
 */
class OracleMetaDataDAOFactory implements MetaDataDAOFactoryConfigInterface
{
    /**
     * Create PDO Metadata DAO instance
     *
     * @param  DriverConfig      $config
     * @return OracleMetaDataDAO
     */
    public static function getInstance(DriverConfig $config)
    {
        $pdoDriver = PdoDriverFactory::getInstance();

        return new OracleMetaDataDAO(
            $pdoDriver->getConnection($config)
        );
    }

    /**
     * @param  MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource)
    {
        $isLoaded = extension_loaded('pdo_oci');
        if (false === $isLoaded) {
            $metadataSource->setFalseDependencie('The extension "pdo_oci" is not loaded');
        }

        return $isLoaded;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataDAO::getDataObject()
    */
    public static function getDescription()
    {
        $pdoDriver = \CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory::getDescription();
        $pdoDriver->getConfig()->response('dsn', \CrudGenerator\MetaData\Driver\Pdo\PdoDriver::ORACLE);

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Oracle")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Oracle\OracleMetaDataDAOFactory')
                   ->setMetadataDao('CrudGenerator\MetaData\Sources\Oracle\OracleMetaDataDAO')
                   ->addDriverDescription($pdoDriver)
                   ->setUniqueName('Oracle');

        return $dataObject;
    }
}
