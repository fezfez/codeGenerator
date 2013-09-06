<?php
namespace CrudGenerator\Tests\ZF2\Tests\Generators\GeneratorFinder;

use CrudGenerator\Generators\GeneratorFinder;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\ClassAwake;

class GetAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        chdir(__DIR__);
        $fileManager = new FileManager();

        $suT = new GeneratorFinder($fileManager, new ClassAwake());

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }
}