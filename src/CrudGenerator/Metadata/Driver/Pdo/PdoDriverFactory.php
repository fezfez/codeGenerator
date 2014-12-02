<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Driver\Pdo;

use CrudGenerator\Metadata\Driver\Driver;
use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\DriverFactoryInterface;

class PdoDriverFactory implements DriverFactoryInterface
{
    /**
     * @return \CrudGenerator\Metadata\Driver\Pdo\PdoDriver
     */
    public static function getInstance()
    {
        return new PdoDriver();
    }

    /**
     * @return \CrudGenerator\Metadata\Driver\Driver
     */
    public static function getDescription()
    {
        $config = new DriverConfig('Web');
        $config->addQuestion('Database Name', 'configDatabaseName');
        $config->addQuestion('Host', 'configHost');
        $config->addQuestion('User', 'configUser');
        $config->addQuestion('Password', 'configPassword');
        $config->addQuestion('Port', 'configPort');

        $dataObject = new Driver();
        $dataObject->setDefinition('Pdo connector')
                   ->setConfig($config)
                   ->setUniqueName('PDO');

        return $dataObject;
    }
}
