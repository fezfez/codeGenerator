<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Driver\File;

use CrudGenerator\MetaData\Driver\DriverConfig;

class FileDriverFactory
{
    /**
     * @param  DriverConfig                                            $driverConfig
     * @throws \Exception
     * @return \CrudGenerator\MetaData\Driver\File\FileDriverInterface
     */
    public static function getInstance(DriverConfig $driverConfig)
    {
        $driverFactory = $driverConfig->getDriver();

        if (false === class_exists($driverFactory, true)) {
            throw new \Exception(sprintf('Driver %s does not exist', $driverFactory));
        }

        $driver = $driverFactory::getInstance();

        if (in_array('CrudGenerator\MetaData\Driver\File\FileDriverInterface', class_implements($driver)) === true) {
            return $driver;
        } else {
            throw new \Exception('Driver must be a file driver '.json_encode(class_implements($driver)));
        }
    }
}
