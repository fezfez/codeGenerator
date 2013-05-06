<?php

namespace CrudGenerator\MetaData;

use Doctrine\Bundle\DoctrineBundle\Mapping\MetadataFactory;
use Doctrine\ORM\EntityManager;

class MetaDataDAO
{
    private $_em = null;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;
    }

    public function getAllMetadata()
    {
        return $this->_em->getMetaDataFactory()->getAllMetadata();
    }

    public function getEntityMetadata($entity)
    {
        return $this->_em->getMetadataFactory()->getMetadataFor($entity);
    }
}
