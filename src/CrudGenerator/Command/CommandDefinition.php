<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Command;

/**
 * Base representation for template generation
 *
 * @author Stéphane Demonchaux
 */
class CommandDefinition
{
    /**
     * @var string
     */
    private $action = null;
    /**
     * @var string
     */
    private $namespace = null;
    /**
     * @var string
     */
    private $definition = null;
    /**
     * @var callable
     */
    private $runner = null;

    /**
     * @param  string                                   $value
     * @return \CrudGenerator\Command\CommandDefinition
     */
    public function setAction($value)
    {
        $this->action = $value;

        return $this;
    }

    /**
     * @param  string                                   $value
     * @return \CrudGenerator\Command\CommandDefinition
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;

        return $this;
    }

    /**
     * @param  string                                   $value
     * @return \CrudGenerator\Command\CommandDefinition
     */
    public function setDefinition($value)
    {
        $this->definition = $value;

        return $this;
    }

    /**
     * @param  callable                                 $value
     * @return \CrudGenerator\Command\CommandDefinition
     */
    public function setRunner(callable $value)
    {
        $this->runner = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    /**
     * @return callable
     */
    public function getRunner()
    {
        return $this->runner;
    }
}
