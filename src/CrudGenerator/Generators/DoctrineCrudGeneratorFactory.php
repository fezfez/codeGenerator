<?php
namespace CrudGenerator\Generators;

use CrudGenerator\View\ZendViewFactory;
use CrudGenerator\FileManager;
use CrudGenerator\Hydrator;
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