<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\ClassAwake;

class GetAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $fileManager = new FileManager();

        $suT = new GeneratorFinder($fileManager, new ClassAwake());

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }
}
