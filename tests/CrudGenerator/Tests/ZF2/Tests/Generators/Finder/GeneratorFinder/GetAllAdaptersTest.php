<?php
namespace CrudGenerator\Tests\ZF2\Tests\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\TranstyperFactory;
use CrudGenerator\Generators\GeneratorCompatibilityChecker;

class GetAllAdaptersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        chdir(__DIR__);

        $suT = new GeneratorFinder(new GeneratorCompatibilityChecker(), TranstyperFactory::getInstance());

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }
}
