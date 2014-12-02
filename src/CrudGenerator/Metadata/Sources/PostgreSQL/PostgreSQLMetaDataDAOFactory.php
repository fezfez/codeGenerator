<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\PostgreSQL;

use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Driver\Pdo\PdoDriver;
use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\Pdo\PdoDriverFactory;
use CrudGenerator\Metadata\Sources\MetaDataDAOFactoryConfigInterface;

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
     * @see \CrudGenerator\Metadata\Sources\MetaDataDAO::getDataObject()
    */
    public static function getDescription()
    {
        $pdoDriver = \CrudGenerator\Metadata\Driver\Pdo\PdoDriverFactory::getDescription();
        $pdoDriver->getConfig()->response('dsn', \CrudGenerator\Metadata\Driver\Pdo\PdoDriver::POSTGRESQL);

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("PostgreSQL")
                   ->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAO")
                   ->addDriverDescription($pdoDriver)
                   ->setUniqueName('PostgreSQL');

        return $dataObject;
    }
}
