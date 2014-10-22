<?php

namespace CrudGenerator\Utils;

use Doctrine\Instantiator\Instantiator;

class ComparatorFactory
{
    /**
     * @return \CrudGenerator\Utils\Comparator
     */
    public static function getInstance()
    {
        return new Comparator(new Instantiator());
    }
}