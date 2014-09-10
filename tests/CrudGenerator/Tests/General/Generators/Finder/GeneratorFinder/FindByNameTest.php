<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Utils\TranstyperFactory;
use CrudGenerator\Generators\GeneratorCompatibilityChecker;

class FindByNameTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        $suT = new GeneratorFinder(new GeneratorCompatibilityChecker(), TranstyperFactory::getInstance());

        $this->setExpectedException('InvalidArgumentException');

        $suT->findByName('fail');
    }

    public function testOk()
    {
        $suT = new GeneratorFinder(new GeneratorCompatibilityChecker(), TranstyperFactory::getInstance());

        $this->assertInternalType(
            'string',
            $suT->findByName('ArchitectGenerator')
        );
    }
}
