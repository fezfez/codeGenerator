<?php
namespace CrudGenerator\Generators;

use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\Generators\GeneratorFinder;
use CrudGenerator\FileManager;

class GeneratorFinderFactory
{
    /**
     * @return \CrudGenerator\Generators\GeneratorFinder
     */
    public static function getInstance()
    {
        $fileManager = new FileManager();

        return new GeneratorFinder($fileManager);
    }
}
