<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace CrudGenerator\MetaData\Driver;

use CrudGenerator\Utils\Comparator;

class DriverValidator
{
    /**
     * @var Comparator
     */
    private $comparator = null;

    /**
     * @param Comparator $comparator
     */
    public function __construct(Comparator $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * @param DriverConfig $config
     */
    public function isValidConfig(DriverConfig $config)
    {
        $this->isDriverFactory($config->getDriver());
    }

    /**
     * @param array $config
     * @throws \Exception
     */
    public function isValidArrayExpression(array $config)
    {
        if ($this->haveDriverConfig($config) === false) {
            return;
        }

        $config = $this->comparator->compareClassAndArray('CrudGenerator\MetaData\Driver\DriverConfig', $config);
        $this->isDriverFactory($config[DriverConfig::FACTORY]);
    }

    /**
     * @param array $config
     * @return boolean
     */
    public function haveDriverConfig(array $config)
    {
        // This config have no driver
        if (isset($config[DriverConfig::FACTORY]) === false) {
            return false;
        }
    }

    /**
     * @param string $driverFactory
     * @throws \Exception
     */
    private function isDriverFactory($driverFactory)
    {
        if (false === class_exists($driverFactory, true)) {
            throw new \Exception(sprintf('Driver "%s" does not exist', $driverFactory));
        }

        $driverFactoryInterface = 'CrudGenerator\MetaData\Driver\DriverFactoryInterface';
        if (in_array($driverFactoryInterface, class_implements($driverFactory)) === false) {
            throw new \Exception(sprintf('Driver "%s" must implement "%s"', $driverFactory, $driverFactoryInterface));
        }
    }
}