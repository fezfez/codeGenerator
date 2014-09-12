<?php
namespace CrudGenerator\Tests\General\Command\CreateCommand;

use CrudGenerator\Command\CreateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateCommand()
    {
        $application    = new Application();
        $createCommand  = new CreateCommand($application);

        // Create a fake mock to make sure thats the callback is call
        $fakeMock = $this->getMockBuilder('CrudGenerator\Command\CreateCommand')
        ->disableOriginalConstructor()
        ->getMock();

        $fakeMock->expects($this->once())
        ->method('create')
        ->with('toto', 'titi', function() { });

        $createCommand->create(
            'my_action',
            'this is the definition of my_action',
            function() use ($fakeMock) {
                $fakeMock->create('toto', 'titi', function() { });
            }
        );

        $sUT = $application->find('generator:my_action');

        $this->assertInstanceOf('CrudGenerator\Command\SkeletonCommand', $sUT);

        $commandTester = new CommandTester($sUT);
        $commandTester->execute(array('command' => $sUT->getName()));
    }
}
