<?php
namespace CrudGenerator\Tests\ZF2\Tests\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Generators\Validator\GeneratorValidatorFactory;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\TranstyperFactory;

class GetAllAdaptersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $suT = new GeneratorFinder(
            TranstyperFactory::getInstance(),
            GeneratorValidatorFactory::getInstance(),
            new FileManager()
        );

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }
}
