<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Driver;

/**
 * Metadata connector interface
 *
 * @author Stéphane Demonchaux
 */
interface DriverInterface
{
    /**
     * @param  DriverConfig    $driverConfig
     * @throws ConfigException
     * @return boolean
     */
    public function isValid(DriverConfig $driverConfig);
}
