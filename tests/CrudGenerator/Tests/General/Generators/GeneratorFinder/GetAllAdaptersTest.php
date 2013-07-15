<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorFinder;

use CrudGenerator\Generators\GeneratorFinder;
use CrudGenerator\FileManager;

class GetAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $fileManager = new FileManager();

        $suT = new GeneratorFinder($fileManager);

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }
}
