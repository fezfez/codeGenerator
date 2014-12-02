<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Config;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Metadata\MetaDataSourceHydratorFactory;
use CrudGenerator\Utils\TranstyperFactory;
use KeepUpdate\ArrayValidatorFactory;
use CrudGenerator\Metadata\Driver\DriverHydrator;

class MetaDataConfigDAOFactory
{
    /**
     * @param  ContextInterface                                 $context
     * @return \CrudGenerator\Metadata\Config\MetaDataConfigDAO
     */
    public static function getInstance(ContextInterface $context)
    {
        return new MetaDataConfigDAO(
            new FileManager(),
            TranstyperFactory::getInstance(),
            ArrayValidatorFactory::getInstance(),
            MetaDataSourceHydratorFactory::getInstance(),
            new DriverHydrator(),
            $context
        );
    }
}
