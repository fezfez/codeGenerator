<?php

namespace CrudGenerator\MetaData;

use Doctrine\ORM\EntityManager;

interface MetaDataDAOInterface
{
    /**
     * @return \CrudGenerator\MetaData\MetaDataDataObjectCollection
     */
    public function getAllMetadata();

    /**
     * @param string $entity
     * @return \CrudGenerator\MetaData\MetaDataDataObject
     */
    public function getMetadataFor($entity);
}
