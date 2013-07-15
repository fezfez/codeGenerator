<?php
namespace CrudGenerator\Generators;

use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\Generators\GeneratorFinder;
use CrudGenerator\FileManager;

/**
 * Create GeneratorFinder instance
 *
 * @author Stéphane Demonchaux
 */
class GeneratorFinderFactory
{
    /**
     * Create GeneratorFinder instance
     *
     * @return \CrudGenerator\Generators\GeneratorFinder
     */
    public static function getInstance()
    {
        $fileManager = new FileManager();

        return new GeneratorFinder($fileManager);
    }
}
