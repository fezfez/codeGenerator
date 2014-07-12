<?php
namespace CrudGenerator\Tests\ZF2\Tests\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\YamlFactory;

class GetAllAdaptersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        chdir(__DIR__);
        $fileManager = new FileManager();

        $suT = new GeneratorFinder($fileManager,  YamlFactory::getInstance());

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }
}
