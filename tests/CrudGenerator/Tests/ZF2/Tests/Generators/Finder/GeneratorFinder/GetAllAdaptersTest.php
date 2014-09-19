<?php
namespace CrudGenerator\Tests\ZF2\Tests\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Utils\TranstyperFactory;
use CrudGenerator\Generators\Validator\GeneratorValidatorFactory;

class GetAllAdaptersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $suT = new GeneratorFinder(TranstyperFactory::getInstance(), GeneratorValidatorFactory::getInstance());

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }
}
