<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Driver\File\Web;

use CrudGenerator\Metadata\Driver\Driver;
use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\DriverFactoryInterface;
use CrudGenerator\Utils\FileManager;

class WebDriverFactory implements DriverFactoryInterface
{
    /**
     * @return \CrudGenerator\Metadata\Driver\File\Web\WebDriver
     */
    public static function getInstance()
    {
        return new WebDriver(new FileManager());
    }

    /**
     * @return \CrudGenerator\Metadata\Driver\Driver
     */
    public static function getDescription()
    {
        $config = new DriverConfig('Web');
        $config->addQuestion('Url', 'configUrl');
        $config->setDriver(__CLASS__);

        $dataObject = new Driver();
        $dataObject->setConfig($config)
                   ->setDefinition('Web connector')
                   ->setUniqueName('Web');

        return $dataObject;
    }
}
