<?php
namespace CrudGenerator\Adapter;

use CrudGenerator\MetaData\AbstractConfig;

class AdapterDataObject
{
    /**
     * @var string name of adapater
     */
    private $name = null;
    /**
     * @var string true if dependencies of adapater are complete
     */
    private $falseDependencies = null;
    /**
     * @var string adapter definition
     */
    private $definition = null;
    /**
     * @var AbstractConfig adapter configuration
     */
    private $config = null;

    /**
     * @param string $value
     * @return \CrudGenerator\Adapter\AdapterDataObject
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\Adapter\AdapterDataObject
     */
    public function setDefinition($value)
    {
        $this->definition = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\Adapter\AdapterDataObject
     */
    public function setFalseDependencie($value)
    {
        $this->falseDependencies = $value;
        return $this;
    }
    /**
     * @param AbstractConfig $value
     * @return \CrudGenerator\Adapter\AdapterDataObject
     */
    public function setConfig(AbstractConfig $value)
    {
        $this->config = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    /**
     * @return string
     */
    public function getFactory()
    {
        return $this->name . 'Factory';
    }
    /**
     * @return string
     */
    public function getFalseDependencies()
    {
        return $this->falseDependencies;
    }
    /**
     * @return AbstractConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
}

