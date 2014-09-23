<?php
namespace CrudGenerator\Tests\General\Generators\Installer\GeneratorInstallerFactory;

use CrudGenerator\Generators\Installer\GeneratorInstallerFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testWithValidSchema()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();


        $this->assertInstanceOf(
            'CrudGenerator\Generators\Installer\GeneratorInstaller',
            GeneratorInstallerFactory::getInstance($context)
        );
    }
}
