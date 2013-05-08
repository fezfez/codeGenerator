<?php
namespace CrudGenerator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

class DataObject
{
    private $module          = null;
    private $entity          = null;
    private $metadata        = null;
    private $writeAction     = null;
    private $namespace       = null;
    private $directory       = null;

    public function setModule($value)
    {
        $this->module = $value;
        return $this;
    }
    public function setEntity($value)
    {
        $this->entity = $value;
        return $this;
    }
    public function setMetadata(ClassMetadataInfo $value)
    {
        $this->metadata = $value;
        return $this;
    }
    public function setWriteAction($value)
    {
        $this->writeAction = $value;
        return $this;
    }
    public function setNamespace($value)
    {
        $this->namespace = $value;
        return $this;
    }
    public function setDirectory($value)
    {
        $this->directory = $value;
        return $this;
    }

    public function getModule()
    {
        return $this->module;
    }
    public function getEntity()
    {
        return $this->entity;
    }
    public function getEntityName()
    {
        if(!strrchr($this->entity, '\\')) {
            return $this->entity;
        } else {
            return str_replace('\\', '', strrchr($this->entity, '\\'));
        }
    }
    public function getMetadata()
    {
        return $this->metadata;
    }
    public function getWriteAction()
    {
        return $this->writeAction;
    }
    public function getNamespace()
    {
        return $this->namespace;
    }
    public function getNamespacePath()
    {
        return str_replace('\\', '/', $this->namespace);
    }
    public function getDirectory()
    {
        return $this->directory;
    }
}