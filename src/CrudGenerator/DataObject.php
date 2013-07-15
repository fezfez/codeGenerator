<?php
namespace CrudGenerator;

use CrudGenerator\MetaData\DataObject\MetaDataDataObject;

/**
 * Base representation for template generation
 *
 * @author Stéphane Demonchaux
 */
class DataObject
{
    /**
     * @var string Module name
     */
    private $module          = null;
    /**
     * @var string Entity name
     */
    private $entity          = null;
    /**
     * @var MetaDataDataObject Metadata object
     */
    private $metadata        = null;
    /**
     * @var string Target namespace
     */
    private $namespace       = null;
    /**
     * @var string Target directory
     */
    private $directory       = null;

    /**
     * Set Module
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setModule($value)
    {
        $this->module = $value;
        return $this;
    }
    /**
     * Set Entity
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setEntity($value)
    {
        $this->entity = $value;
        return $this;
    }
    /**
     * Set MetaData
     * @param MetaDataDataObject $value
     * @return \CrudGenerator\DataObject
     */
    public function setMetadata(MetaDataDataObject $value)
    {
        $this->metadata = $value;
        return $this;
    }
    /**
     * Set Namespace
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
        return $this;
    }
    /**
     * Set Directory
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setDirectory($value)
    {
        $this->directory = $value;
        return $this;
    }

    /**
     * Get Module
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }
    /**
     * Get Entity
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }
    /**
     * Get Entity name
     * @return string
     */
    public function getEntityName()
    {
        if (!strrchr($this->entity, '\\')) {
            return $this->entity;
        } else {
            return str_replace('\\', '', strrchr($this->entity, '\\'));
        }
    }
    /**
     * Get MetaData
     * @return \CrudGenerator\MetaData\DataObject\MetaDataDataObject
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
    /**
     * Set Namespace
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    /**
     * Get namespace path
     * @return string
     */
    public function getNamespacePath()
    {
        return str_replace('\\', '/', $this->namespace);
    }
    /**
     * Get directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}
