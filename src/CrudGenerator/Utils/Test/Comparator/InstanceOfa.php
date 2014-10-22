<?php

namespace CrudGenerator\Utils\Test\Comparator;

/**
 * @Annotation
 *
 */
class InstanceOfa
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

        if ($this->class !== $value) {
            throw new \Exception(sprintf('Class "%s" must be "%s"', $this->class, $value));
        }
    }
}