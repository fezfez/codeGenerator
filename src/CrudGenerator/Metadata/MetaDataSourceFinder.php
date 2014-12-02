<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata;

use CrudGenerator\Utils\ClassAwake;

/**
 * Find all MetaDataSource allow in project
 *
 * @author Stéphane Demonchaux
 */
class MetaDataSourceFinder
{
    /**
     * @var ClassAwake Class awake
     */
    private $classAwake = null;
    /**
     * @var MetaDataSourceHydrator
     */
    private $metaDataSourceHydrator = null;

    /**
     * Find all adapters allow in project
     * @param ClassAwake             $classAwake
     * @param MetaDataSourceHydrator $metaDataSourceHydrator
     */
    public function __construct(ClassAwake $classAwake, MetaDataSourceHydrator $metaDataSourceHydrator)
    {
        $this->classAwake             = $classAwake;
        $this->metaDataSourceHydrator = $metaDataSourceHydrator;
    }

    /**
     * Find all adpater in the projects
     *
     * @return MetaDataSourceCollection
     */
    public function getAllAdapters()
    {
        $classCollection = $this->classAwake->wakeByInterfaces(
            array(
                __DIR__ . '/Sources/',
            ),
            'CrudGenerator\Metadata\Sources\MetaDataDAOFactoryInterface'
        );

        $adapterCollection = new MetaDataSourceCollection();

        foreach ($classCollection as $className) {
            $adapterCollection->append(
                $this->metaDataSourceHydrator->adapterNameToMetaDataSource(
                    $className
                )
            );
        }

        return $adapterCollection;
    }
}
