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

use CrudGenerator\MetaData\MetaDataSource;

/**
 * Find all MetaDataSource allow in project
 *
 * @author Stéphane Demonchaux
 */
class MetaDataSourceHydrator
{
    /**
     * Build a MetaDataSourceDataobject with all his dependencies
     *
     * @param string $adapterClassName
     * @return MetaDataSource
     */
    public function adapterNameToMetaDataSource($metadataSourceClassName)
    {
        /* @var $metadataSourceClassName \CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface */
        $metaDataSource = $metadataSourceClassName::getDescription();
        $metadataSourceClassName::checkDependencies($metaDataSource);

        return $metaDataSource;
    }
}
