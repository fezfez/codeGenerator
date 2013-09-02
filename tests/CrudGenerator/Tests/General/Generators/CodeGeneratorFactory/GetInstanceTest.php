<?php
namespace CrudGenerator\Tests\General\Generators\CodeGeneratorFactory;

use CrudGenerator\Generators\CodeGeneratorFactory;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();
        $strategy =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $codeGenerator = new CodeGeneratorFactory($strategy);
        $this->assertInstanceOf(
            'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator',
            $codeGenerator->create(
                $stubOutput,
                new DialogHelper(),
                'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
            )
        );
    }
}
