<?php
namespace CrudGenerator\Generators;

use CrudGenerator\Generators\View\ZendViewFactory;
use CrudGenerator\Generators\FileManager;
use CrudGenerator\Generators\Hydrator;
use Symfony\Component\Console\Output\OutputInterface;

class DoctrineCrudGeneratorFactory
{
    private function __construct()
    {
        
    }

    public static function getInstance(OutputInterface $output, $class)
    {
        $zendView    = ZendViewFactory::getInstance();
        $fileManager = new FileManager();
        $hydrator    = new Hydrator();

        return new $class($zendView, $output, $fileManager, $hydrator);
    }
}