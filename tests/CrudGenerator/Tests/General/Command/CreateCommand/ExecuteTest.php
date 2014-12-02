<?php
namespace CrudGenerator\Tests\General\Command\CreateCommand;

use CrudGenerator\Command\CreateCommand;
use CrudGenerator\Tests\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ExecuteTest extends TestCase
{
    public function testCreateCommand()
    {
        $application   = new Application();
        $createCommand = new CreateCommand($application);

        // Create a fake mock to make sure thats the callback is call
        $fakeMock = $this->createMock('CrudGenerator\Context\CliContext');

        $fakeMockExpects = $fakeMock->expects($this->once());
        $fakeMockExpects->method('log');
        $fakeMockExpects->with('hello !');

        $createCommand->create(
            'my_action',
            'this is the definition of my_action',
            function () use ($fakeMock) {
                $fakeMock->log('hello !');
            }
        );

        $sUT = $application->find('generator:my_action');

        $this->assertInstanceOf('CrudGenerator\Command\SkeletonCommand', $sUT);

        $commandTester = new CommandTester($sUT);
        $commandTester->execute(array('command' => $sUT->getName()));
    }
}
