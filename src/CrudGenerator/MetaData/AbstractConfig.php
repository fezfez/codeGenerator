<?php
namespace CrudGenerator\MetaData;

abstract class AbstractConfig
{
    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
}
