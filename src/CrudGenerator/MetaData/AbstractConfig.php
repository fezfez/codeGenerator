<?php
namespace CrudGenerator\MetaData;

/**
 * Abstract Metadata config
 *
 * @author Stéphane Demonchaux
 */
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
