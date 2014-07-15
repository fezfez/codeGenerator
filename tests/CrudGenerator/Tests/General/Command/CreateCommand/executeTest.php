<?php
namespace CrudGenerator\Tests\General\Command\CreateCommand;

use CrudGenerator\Command\CreateCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\CommandTester;

class executeTest extends \PHPUnit_Framework_TestCase
{
    public function testYes()
    {
        $mainBackboneStub = $this->getMockBuilder('CrudGenerator\Backbone\MainBackbone')
        ->disableOriginalConstructor()
        ->getMock();

        $mainBackboneStub->expects($this->once())
        ->method('run');

        $commandTmp = new CreateCommand($mainBackboneStub);
        $application = new App();
        $application->add($commandTmp);

        $sUT = $application->find('CodeGenerator:create');

        $commandTester = new CommandTester($sUT);

        $commandTester->execute(array('command' => $sUT->getName()));
    }
}
