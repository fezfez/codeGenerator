<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData;

use CrudGenerator\Utils\ClassAwake;

/**
 * Create MetaDataSourceFinder instance
 *
 * @author Stéphane Demonchaux
 */
class MetaDataSourceFinderFactory
{
    /**
     * Create MetaDataSourceFinder instance
     *
     * @return \CrudGenerator\MetaData\MetaDataSourceFinder
     */
    public static function getInstance()
    {
        $metaDataSourceHydrator = MetaDataSourceHydratorFactory::getInstance();
        $classAwake             = new ClassAwake();

        return new MetaDataSourceFinder($classAwake, $metaDataSourceHydrator);
    }
}
