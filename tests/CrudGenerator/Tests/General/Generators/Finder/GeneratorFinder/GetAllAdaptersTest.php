<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\YamlFactory;
use CrudGenerator\Generators\GeneratorCompatibilityChecker;

class GetAllAdaptersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $fileManager = new FileManager();
        $yaml        = YamlFactory::getInstance();

        $suT = new GeneratorFinder($fileManager, $yaml, new GeneratorCompatibilityChecker());

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }
}
