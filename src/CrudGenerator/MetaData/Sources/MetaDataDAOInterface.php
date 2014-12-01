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

/**
 * Metadata DAO interface
 *
 * @author Stéphane Demonchaux
 */
interface MetaDataDAOInterface
{
    /**
     * Get all metadata from the concrete metadata DAO
     *
     * @return \CrudGenerator\MetaData\DataObject\MetaDataCollection
     */
    public function getAllMetadata();

    /**
     * Get particularie metadata from the concrete metadata DAO
     *
     * @param  string                                      $entityName
     * @return \CrudGenerator\MetaData\DataObject\MetaData
     */
    public function getMetadataFor($entityName, array $parentName = array());
}
