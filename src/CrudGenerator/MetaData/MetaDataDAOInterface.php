<?php

namespace CrudGenerator\MetaData;

use Doctrine\ORM\EntityManager;

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
