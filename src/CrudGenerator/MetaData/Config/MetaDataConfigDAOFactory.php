<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Config;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\MetaData\MetaDataSourceHydratorFactory;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\MetaData\Driver\DriverValidator;
use CrudGenerator\Utils\TranstyperFactory;

class MetaDataConfigDAOFactory
{
    /**
     * @param ContextInterface $context
     * @return \CrudGenerator\MetaData\Config\MetaDataConfigDAO
     */
    public static function getInstance(ContextInterface $context)
    {
        return new MetaDataConfigDAO(
            new FileManager(),
            TranstyperFactory::getInstance(),
            new DriverValidator(),
            MetaDataSourceHydratorFactory::getInstance(),
            $context
        );
    }
}
