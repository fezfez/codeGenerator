<?php
namespace CrudGenerator\Generators;

use CrudGenerator\View\ZendViewFactory;
use CrudGenerator\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;

class DoctrineCrudGeneratorFactory
{
    private function __construct()
    {

    }

    /**
     * @param OutputInterface $output
     * @param InputInterface $input
     * @param DialogHelper $dialog
     * @param unknown $class
     * @return  CrudGenerator\Generators\BaseCodeGenerator
     */
    public static function getInstance(OutputInterface $output, InputInterface $input, DialogHelper $dialog, $class)
    {
        $zendView          = ZendViewFactory::getInstance();
        $fileManager       = new FileManager();
        $generiqueQuestion = new GeneriqueQuestions($dialog, $output);

        return new $class($zendView, $output, $fileManager, $dialog, $input, $generiqueQuestion);
    }
}