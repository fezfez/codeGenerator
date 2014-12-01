<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Sources\PostgreSQL;

use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriver;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryConfigInterface;

/**
 * Create PostgreSQL Metadata DAO instance
 *
 */
class PostgreSQLMetaDataDAOFactory implements MetaDataDAOFactoryConfigInterface
{
    /**
     * Create PostgreSQL Metadata DAO instance
     *
     * @return PostgreSQLMetaDataDAO
     */
    public static function getInstance(DriverConfig $config)
    {
        $pdoDriver = PdoDriverFactory::getInstance();

        return new PostgreSQLMetaDataDAO(
            $pdoDriver->getConnection($config),
            $config,
            new SqlManager()
        );
    }

    /**
     * @param  MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource)
    {
        $isLoaded = extension_loaded('pdo_pgsql');
        if (false === $isLoaded) {
            $metadataSource->setFalseDependencie('The extension "pdo_pgsql" is not loaded');
        }

        return $isLoaded;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataDAO::getDataObject()
    */
    public static function getDescription()
    {
        $pdoDriver = \CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory::getDescription();
        $pdoDriver->getConfig()->response('dsn', \CrudGenerator\MetaData\Driver\Pdo\PdoDriver::POSTGRESQL);

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("PostgreSQL")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAO")
                   ->addDriverDescription($pdoDriver)
                   ->setUniqueName('PostgreSQL');

        return $dataObject;
    }
}
