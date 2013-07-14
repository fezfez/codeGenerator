<?php
namespace CrudGenerator\Adapter;

use CrudGenerator\Adapter\AdapterFinder;
use CrudGenerator\FileManager;

class AdapterFinderFactory
{
    public static function getInstance()
    {
        $fileManager = new FileManager();

        return new AdapterFinder($fileManager);
    }

}
