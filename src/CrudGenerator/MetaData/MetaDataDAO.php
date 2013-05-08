<?php

namespace CrudGenerator\MetaData;

use Doctrine\ORM\EntityManager;

class MetaDataDAO
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em = null;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getAllMetadata()
    {
        return $this->em->getMetaDataFactory()->getAllMetadata();
    }

    /**
     * @param string $entity
     * @return \Doctrine\ORM\Mapping\ClassMetadataInfo
     */
    public function getEntityMetadata($entity)
    {
        return $this->em->getMetadataFactory()->getMetadataFor($entity);
    }
}
