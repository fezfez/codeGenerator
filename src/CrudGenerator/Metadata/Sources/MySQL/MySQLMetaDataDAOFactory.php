<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\MySQL;

use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\Pdo\PdoDriver;
use CrudGenerator\Metadata\Driver\Pdo\PdoDriverFactory;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\MetaDataDAOFactoryConfigInterface;

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
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public static function getDescription()
    {
        $pdoDriver = \CrudGenerator\Metadata\Driver\Pdo\PdoDriverFactory::getDescription();
        $pdoDriver->getConfig()->response('dsn', \CrudGenerator\Metadata\Driver\Pdo\PdoDriver::MYSQL);

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("MySQL")
                   ->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\MySQL\MySQLMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\Metadata\Sources\MySQL\MySQLMetaDataDAO")
                   ->addDriverDescription($pdoDriver)
                   ->setUniqueName("MySQL");

        return $dataObject;
    }
}
