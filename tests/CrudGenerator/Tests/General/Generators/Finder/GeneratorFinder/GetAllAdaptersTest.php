<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Utils\TranstyperFactory;
use CrudGenerator\Generators\GeneratorCompatibilityChecker;
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
