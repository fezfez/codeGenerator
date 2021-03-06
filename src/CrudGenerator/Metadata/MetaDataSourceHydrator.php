<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata;

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
     * @param  string         $metadataSourceClassName MetadataSource class name
     * @return MetaDataSource
     */
    public function adapterNameToMetaDataSource($metadataSourceClassName)
    {
        /* @var $metadataSourceClassName \CrudGenerator\Metadata\Sources\MetaDataDAOFactoryInterface */
        $metaDataSource = $metadataSourceClassName::getDescription();
        $metadataSourceClassName::checkDependencies($metaDataSource);

        return $metaDataSource;
    }
}
