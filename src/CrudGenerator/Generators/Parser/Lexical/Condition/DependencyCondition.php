<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical\Condition;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParser;

class DependencyCondition implements ConditionInterface
{
    const NAME = 'dependency';

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function isValid(
        array $nodes,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    ) {
        $dependencies = array();

        foreach ($generator->getDependencies() as $dependency) {
            $dependencies[] = $dependency->getName();
        }

        foreach ($nodes as $node) {
            if (($this->differentCondition($dependencies, $node) === true ||
                $this->equalCondition($dependencies, $node)) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array   $generatorDependencies
     * @param  string  $dependencyName
     * @return boolean
     */
    private function differentCondition(array $generatorDependencies, $dependencyName)
    {
        $isTrue = false;

        if (substr($dependencyName, -strlen(ConditionInterface::UNDEFINED)) === ConditionInterface::UNDEFINED &&
            false === in_array(
                substr($dependencyName, 0, -count(ConditionInterface::UNDEFINED)),
                $generatorDependencies
            )
        ) {
            $isTrue = true;
        }

        return $isTrue;
    }

    /**
     * @param  array   $generatorDependencies
     * @param  string  $dependencyName
     * @return boolean
     */
    private function equalCondition(array $generatorDependencies, $dependencyName)
    {
        $isTrue = false;

        if (in_array($dependencyName, $generatorDependencies) === true) {
            $isTrue = true;
        }

        return $isTrue;
    }
}
