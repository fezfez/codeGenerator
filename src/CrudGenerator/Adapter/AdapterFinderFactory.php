<?php
namespace CrudGenerator\Adapter;

use CrudGenerator\Adapter\AdapterFinder;
use CrudGenerator\FileManager;

/**
 * Create AdapterFinder instance
 *
 * @author Stéphane Demonchaux
 */
class AdapterFinderFactory
{
    /**
     * Create AdapterFinder instance
     *
     * @return \CrudGenerator\Adapter\AdapterFinder
     */
    public static function getInstance()
    {
        $fileManager = new FileManager();

        return new AdapterFinder($fileManager);
    }

}
