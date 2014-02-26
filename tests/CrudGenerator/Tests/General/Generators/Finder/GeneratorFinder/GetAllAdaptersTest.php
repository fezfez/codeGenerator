<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Utils\FileManager;

class GetAllAdaptersTest extends \PHPUnit_Framework_TestCase
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
