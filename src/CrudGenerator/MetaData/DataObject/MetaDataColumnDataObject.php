<?php

namespace CrudGenerator\MetaData\DataObject;

class MetaDataColumnDataObject
{
    /**
     * @var string
     */
    private $name = null;
    /**
     * @var string
     */
    private $type = null;
    /**
     * @var integer
     */
    private $length = null;
    /**
     * @var boolean
     */
    private $nullable = true;

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\MetaDataColumnDataObject
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\MetaDataColumnDataObject
     */
    public function setType($value)
    {
        $this->type = $value;
        return $this;
    }
    /**
     * @param integer $value
     * @return \CrudGenerator\MetaData\MetaDataColumnDataObject
     */
    public function setLength($value)
    {
        $this->length = $value;
        return $this;
    }
    /**
     * @param boolean $value
     * @return \CrudGenerator\MetaData\MetaDataColumnDataObject
     */
    public function setNullable($value)
    {
        $this->nullable = $value;
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
    public function getType()
    {
        return $this->type;
    }
    /**
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }
    /**
     * @return boolean
     */
    public function getNullable()
    {
        return $this->nullable;
    }
}
