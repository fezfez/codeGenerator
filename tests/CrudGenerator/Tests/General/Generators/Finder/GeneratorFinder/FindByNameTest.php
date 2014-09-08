<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\YamlFactory;
use CrudGenerator\Generators\GeneratorCompatibilityChecker;

class FindByNameTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        $fileManager = new FileManager();

        $suT = new GeneratorFinder($fileManager, YamlFactory::getInstance(), new GeneratorCompatibilityChecker());

        $this->setExpectedException('InvalidArgumentException');

        $suT->findByName('fail');
    }

    public function testOk()
    {
        $fileManager = new FileManager();

        $suT = new GeneratorFinder($fileManager, YamlFactory::getInstance(), new GeneratorCompatibilityChecker());

        $this->assertInternalType(
            'string',
            $suT->findByName('ArchitectGenerator')
        );
    }
}
