<?php
namespace CrudGenerator\Tests\General\Backbone\SearchGeneratorBackbone;

use CrudGenerator\Backbone\SearchGeneratorBackbone;
use CrudGenerator\Generators\GeneratorDataObject;

class RunTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectlyCall()
    {
        $generatorSearch    = $this->createMock('CrudGenerator\Generators\Search\GeneratorSearch');
        $generatorInstaller = $this->createMock('CrudGenerator\Generators\Installer\GeneratorInstallerInterface');
        $generatorDetail    = $this->createMock('CrudGenerator\Generators\Detail\GeneratorDetail');
        $context            = $this->createMock('CrudGenerator\Context\CliContext');

        $context->expects($this->exactly(2))
        ->method('confirm')
        ->willReturn(true);

        $generatorSearch->expects($this->once())
        ->method('ask')
        ->willReturn(new \Packagist\Api\Result\Result());

        $generatorDetail->expects($this->once())
        ->method('find');

        $generatorInstaller->expects($this->once())
        ->method('install');

        $sUT = new SearchGeneratorBackbone(
            $generatorSearch,
            $generatorInstaller,
            $generatorDetail,
            $context
        );

        $sUT->run();
    }

    /**
     * @param string $class
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createMock($class)
    {
        return $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();
    }
}
