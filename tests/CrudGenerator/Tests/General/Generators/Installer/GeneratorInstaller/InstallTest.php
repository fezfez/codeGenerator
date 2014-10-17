<?php
namespace CrudGenerator\Tests\General\Generators\Installer\GeneratorInstallerFactory;

use CrudGenerator\Generators\Installer\GeneratorInstaller;
use Symfony\Component\Console\Input\ArrayInput;
use Composer\Command\RequireCommand;
use CrudGenerator\Utils\OutputWeb;

class InstallTest extends \PHPUnit_Framework_TestCase
{
    public function testInstallWithMock()
    {
        $arrayInput     = $this->getMockWithoutConstructor('Symfony\Component\Console\Input\ArrayInput');
        $requireCommand = $this->getMockWithoutConstructor('Composer\Command\RequireCommand');
        $outputWeb      = $this->getMockWithoutConstructor('CrudGenerator\Utils\OutputWeb');

        $arrayInput->expects($this->once())
        ->method('setArgument');

        $requireCommand->expects($this->once())
        ->method('run')
        ->willReturn(1);

        $sUT = new GeneratorInstaller($arrayInput, $requireCommand, $outputWeb);

        $this->assertEquals(1, $sUT->install('MyPackage'));
    }

    /**
     * @param string $class
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockWithoutConstructor($class)
    {
        return $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();
    }
}
