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

use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\MetaData\Driver\DriverConfig;

/**
 * @author Stéphane Demonchaux
 */
interface FileDriverInterface
{
    /**
     * @param  DriverConfig    $driverConfig
     * @throws ConfigException
     * @return string
     */
    public function getFile(DriverConfig $driverConfig);
}
