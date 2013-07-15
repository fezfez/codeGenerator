<?php

namespace CrudGenerator\MetaData;

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
     * @return \CrudGenerator\MetaData\MetaDataDataObjectCollection
     */
    public function getAllMetadata();

    /**
     * Get particularie metadata from the concrete metadata DAO
     *
     * @param string $entityName
     * @return \CrudGenerator\MetaData\MetaDataDataObject
     */
    public function getMetadataFor($entityName);
}
