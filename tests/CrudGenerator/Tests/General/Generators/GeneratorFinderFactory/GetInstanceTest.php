<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorFinder;

use CrudGenerator\Generators\GeneratorFinderFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\Generators\GeneratorFinder',
            GeneratorFinderFactory::getInstance()
        );
    }
}
