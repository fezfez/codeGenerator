<?php
namespace CrudGenerator\Tests\General\Generators\Installer\GeneratorInstallerFactory;

use CrudGenerator\Generators\Installer\GeneratorInstaller;
use Symfony\Component\Console\Input\ArrayInput;
use Composer\Command\RequireCommand;
use CrudGenerator\Utils\OutputWeb;

class InstallTest extends \PHPUnit_Framework_TestCase
{
    public function testWithMock()
    {
        $arrayInput = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
        ->disableOriginalConstructor()
        ->getMock();
        $requireCommand = $this->getMockBuilder('Composer\Command\RequireCommand')
        ->disableOriginalConstructor()
        ->getMock();
        $outputWeb = $this->getMockBuilder('CrudGenerator\Utils\OutputWeb')
        ->disableOriginalConstructor()
        ->getMock();

        $arrayInput->expects($this->once())
        ->method('setArgument');

        $requireCommand->expects($this->once())
        ->method('run');

        $sUT = new GeneratorInstaller($arrayInput, $requireCommand, $outputWeb);

        $sUT->install('MyPackage');
    }
}
