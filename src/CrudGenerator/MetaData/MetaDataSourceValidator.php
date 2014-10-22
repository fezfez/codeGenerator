<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData;

use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\Driver;
use CrudGenerator\MetaData\Driver\DriverValidator;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\Utils\Comparator;

/**
 * Adapter representation
 * @author Stéphane Demonchaux
 */
class MetaDataSourceValidator
{
    /**
     * @var DriverValidator
     */
    private $driverValidator = null;
    /**
     * @var ClassAwake
     */
    private $classAwake = null;

    /**
     * @var Comparator
     */
    private $comparator = null;

    /**
     * @param DriverValidator $driverValidator
     * @param ClassAwake $classAwake
     * @param Comparator $comparator
     */
    public function __construct(DriverValidator $driverValidator, ClassAwake $classAwake, Comparator $comparator)
    {
        $this->driverValidator = $driverValidator;
        $this->classAwake      = $classAwake;
        $this->comparator      = $comparator;
    }

    /**
     * @param MetaDataSource $metadataSource
     */
    public function isValidConfig(MetaDataSource $metadataSource)
    {
        $config = $metadataSource->getConfig();
        if ($config !== null) {
            $this->driverValidator->isValidConfig($config);
        }
    }

    /**
     * @param array $config
     * @param array $allowedMetadataDAO
     * @throws \Exception
     */
    public function isValidArrayExpression(array $config)
    {
        $allowedMetadataDAO = $this->classAwake->wakeByInterfaces(
            array(
                __DIR__ . '/../Sources/'
            ),
            'CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface'
        );

        $this->comparator->compareClassAndArray('CrudGenerator\MetaData\MetaDataSource', $config);

        if (false === in_array($configFile[MetaDataSource::METADATA_DAO_FACTORY], $allowedMetadataDAO)) {
            throw new \Exception(
                sprintf('MetaDataDAOFactoryInterface "%s" does not exist', MetaDataSource::METADATA_DAO_FACTORY)
            );
        }

        $this->driverValidator->isValidArrayExpression($config);
    }
}
