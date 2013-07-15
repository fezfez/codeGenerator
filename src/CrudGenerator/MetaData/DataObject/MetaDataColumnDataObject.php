<?php

namespace CrudGenerator\MetaData\DataObject;

class MetaDataColumnDataObject
{
    /**
     * @var string Column name
     */
    private $name = null;
    /**
     * @var string Column type
     */
    private $type = null;
    /**
     * @var integer Column length
     */
    private $length = null;
    /**
     * @var boolean Column is nullable
     */
    private $nullable = true;

    /**
     * Set Column name
     *
     * @param string $value
     * @return \CrudGenerator\MetaData\MetaDataColumnDataObject
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }
    /**
     * Set Column type
     *
     * @param string $value
     * @return \CrudGenerator\MetaData\MetaDataColumnDataObject
     */
    public function setType($value)
    {
        $this->type = $value;
        return $this;
    }
    /**
     * Set Column length
     *
     * @param integer $value
     * @return \CrudGenerator\MetaData\MetaDataColumnDataObject
     */
    public function setLength($value)
    {
        $this->length = $value;
        return $this;
    }
    /**
     * Set Column is nullable
     *
     * @param boolean $value
     * @return \CrudGenerator\MetaData\MetaDataColumnDataObject
     */
    public function setNullable($value)
    {
        $this->nullable = $value;
        return $this;
    }

    /**
     * Get Column name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Get Column type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Get Column length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }
    /**
     * Get Column is nullable
     *
     * @return boolean
     */
    public function getNullable()
    {
        return $this->nullable;
    }
}
