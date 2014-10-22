<?php

namespace CrudGenerator\Utils\Test\Comparator;

/**
 * @Annotation
 *
 */
class Main
{
    /**
     * @var boolean
     */
    public $strictMode;

    public function exec($keys, $classInstance)
    {
        if ($this->strictMode === true) {
            foreach ($keys as $key) {
                $textHelper = new \CrudGenerator\View\Helpers\Text();
                if (method_exists($classInstance, 'set' . $textHelper->toCamelCase($key, true)) === false) {
                    throw new \Exception(sprintf('"%s" not sync with "%s"', $key, $textHelper->toCamelCase($key, true)));
                }
            }
        }
    }
}