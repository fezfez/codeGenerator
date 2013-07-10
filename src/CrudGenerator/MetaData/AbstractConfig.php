<?php
namespace CrudGenerator\MetaData;

abstract class AbstractConfig
{
    /**
     * Get config definition
     *
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
}
