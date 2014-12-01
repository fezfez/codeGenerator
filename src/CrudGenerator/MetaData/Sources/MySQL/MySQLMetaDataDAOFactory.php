<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Sources\MySQL;

use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriver;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryConfigInterface;

/**
 * Create MySQL Metadata DAO instance
 *
 */
class MySQLMetaDataDAOFactory implements MetaDataDAOFactoryConfigInterface
{
    /**
     * Create MySQL Metadata DAO instance
     *
     * @return MySQLMetaDataDAO
     */
    public static function getInstance(DriverConfig $config)
    {
        $pdoDriver = PdoDriverFactory::getInstance();

        return new MySQLMetaDataDAO(
            $pdoDriver->getConnection($config),
            $config
        );
    }

    /**
     * @param  MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource)
    {
        $isLoaded = extension_loaded('pdo_mysql');
        if (false === $isLoaded) {
            $metadataSource->setFalseDependencie('The extension "pdo_mysql" is not loaded');
        }

        return $isLoaded;
    }

    /**
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public static function getDescription()
    {
        $pdoDriver = \CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory::getDescription();
        $pdoDriver->getConfig()->response('dsn', \CrudGenerator\MetaData\Driver\Pdo\PdoDriver::MYSQL);

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("MySQL")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAO")
                   ->addDriverDescription($pdoDriver)
                   ->setUniqueName("MySQL");

        return $dataObject;
    }
}
