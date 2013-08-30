<?php
namespace CrudGenerator\Tests\General\Command\GeneratorSandBoxCommand;

use CrudGenerator\Generators\ArchitectGenerator\Architect;

use CrudGenerator\History\History;

use CrudGenerator\Command\GeneratorSandBoxCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;

class executeTest extends \PHPUnit_Framework_TestCase
{
   public function testInstance()
    {
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub->expects($this->once())
        ->method('getDTO')
        ->will($this->returnValue('\CrudGenerator\Generators\ArchitectGenerator\Architect'));

        $GeneratorQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\GeneratorQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $GeneratorQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($ArchitectGeneratorStub));

        $application = new App();
        $application->add(new GeneratorSandBoxCommand($GeneratorQuestionStub));

        $command = $application->find('CodeGenerator:generator-sand-box');


        $commandTester = new CommandTester($command);

        $commandTester->execute(array('command' => $command->getName()));
    }
}
