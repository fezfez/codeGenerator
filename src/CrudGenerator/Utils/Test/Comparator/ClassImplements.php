<?php

namespace CrudGenerator\Utils\Test\Comparator;

/**
 * @Annotation
 *
 */
class ClassImplements
{
    /**
     * @var string
     */
    public $class;
    /**
     * @var boolean
     */
    public $optional;

    public function exec($value)
    {
        if ($this->optional === true && $value === null) {
            return;
        }

        if (false === class_exists($value, true)) {
            throw new \Exception(sprintf('Class "%s" does not exist', $value));
        }

        if (in_array($this->class, class_implements($value)) === false) {
            throw new \Exception(sprintf('Class "%s" must implement "%s"', $this->class, $value));
        }
    }
}