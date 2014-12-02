<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Driver\File;

use CrudGenerator\Metadata\Driver\DriverConfig;

class FileDriverFactory
{
    /**
     * @param  DriverConfig                                            $driverConfig
     * @throws \Exception
     * @return \CrudGenerator\Metadata\Driver\File\FileDriverInterface
     */
    public static function getInstance(DriverConfig $driverConfig)
    {
        $driverFactory = $driverConfig->getDriver();

        if (false === class_exists($driverFactory, true)) {
            throw new \Exception(sprintf('Driver %s does not exist', $driverFactory));
        }

        $driver = $driverFactory::getInstance();

        if (in_array('CrudGenerator\Metadata\Driver\File\FileDriverInterface', class_implements($driver)) === true) {
            return $driver;
        } else {
            throw new \Exception('Driver must be a file driver ' . json_encode(class_implements($driver)));
        }
    }
}
