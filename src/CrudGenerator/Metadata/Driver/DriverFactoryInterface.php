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
interface DriverFactoryInterface
{
    /**
     * @throws ConfigException
     * @return \CrudGenerator\Metadata\Driver\DriverInterface
     */
    public static function getInstance();

    /**
     * @return Driver
     */
    public static function getDescription();
}
