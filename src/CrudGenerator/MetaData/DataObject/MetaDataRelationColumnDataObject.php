<?php

namespace CrudGenerator\MetaData\DataObject;

use ArrayObject;

class MetaDataRelationColumnDataObject
{
    /**
     * @var string
     */
    private $name = null;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
