<?php
namespace CrudGenerator\Tests\Generators\GeneratorFinder;

use CrudGenerator\Generators\GeneratorFinder;

class getAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $suT = new GeneratorFinder();

        $this->assertInternalType(
            'array',
             $suT->getAllClasses()
        );
    }
}

