<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace CrudGenerator\Metadata\Driver;

/**
 * @author Stéphane Demonchaux
 */
class Driver
{
    /**
     * @var string
     */
    private $definition = null;
    /**
     * @var DriverConfig
     */
    private $config = null;
    /**
     * Unique name
     *
     * @var string
     */
    private $uniqueName = null;

    /**
     * @param  string $value
     * @return Driver
     */
    public function setDefinition($value)
    {
        $this->definition = $value;

        return $this;
    }
    /**
     * @param  DriverConfig $value
     * @return Driver
     */
    public function setConfig(DriverConfig $value)
    {
        $this->config = $value;

        return $this;
    }
    /**
     * @param  string $value
     * @return Driver
     */
    public function setUniqueName($value)
    {
        $this->uniqueName = $value;

        return $this;
    }
    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    /**
     * @return DriverConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getUniqueName()
    {
        if ($this->config === null) {
            return $this->definition;
        } else {
            return $this->config->getUniqueName();
        }
    }
}
