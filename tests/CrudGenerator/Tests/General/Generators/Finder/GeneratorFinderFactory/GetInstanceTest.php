<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinderFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\Generators\Finder\GeneratorFinder',
            GeneratorFinderFactory::getInstance()
        );
    }
}
