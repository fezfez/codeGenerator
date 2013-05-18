<?php
namespace CrudGenerator\Adapter;

class AdapterDataObject
{
    /**
     * @var string
     */
    private $name = null;
    /**
     * @var array
     */
    private $falseDependencies = array();

    /**
     * @var string
     */
    private $definition = null;

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
    public function addFalseDependencie($value)
    {
        $this->falseDependencies[] = $value;
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
     * @return array
     */
    public function getFalseDependencies()
    {
        return $this->falseDependencies;
    }
}

