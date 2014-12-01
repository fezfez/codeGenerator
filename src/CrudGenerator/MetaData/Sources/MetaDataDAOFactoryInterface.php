<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Sources;

use CrudGenerator\MetaData\MetaDataSource;

/**
 * Metadata DAO Factory interface
 *
 * @author Stéphane Demonchaux
 */
interface MetaDataDAOFactoryInterface
{
    /**
     * Check if dependencies are complete
     *
     * @param  MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource);

    /**
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public static function getDescription();
}
