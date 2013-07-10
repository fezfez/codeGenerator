<?php
namespace CrudGenerator\Generators;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Diff\DiffPHP;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;

class CodeGeneratorFactory
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
        $view              = ViewFactory::getInstance();
        $fileManager       = new FileManager();
        $generiqueQuestion = new GeneriqueQuestions($dialog, $output);
        $diffPHP           = new DiffPHP();

        return new $class($view, $output, $fileManager, $dialog, $input, $generiqueQuestion, $diffPHP);
    }
}
