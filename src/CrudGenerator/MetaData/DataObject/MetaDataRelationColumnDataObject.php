<?php

namespace CrudGenerator\MetaData\DataObject;

use ArrayObject;

class MetaDataRelationColumnDataObject
{
    /**
     * @var string
     */
    private $fullName = null;

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\MetaDataColumnDataObject
     */
    public function setFullName($value)
    {
        $this->fullName = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if (!strrchr($this->fullName, '\\')) {
            return $this->fullName;
        } else {
            return str_replace('\\', '', strrchr($this->fullName, '\\'));
        }
    }
}
