<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\SandBoxStrategy;

use CrudGenerator\Generators\Strategies\SandBoxStrategy;
use Symfony\Component\Console\Helper\DialogHelper;

class IfDirDoesNotExistCreateTest extends \PHPUnit_Framework_TestCase
{
    public function testWithGenerate()
    {
        $view = $this->getMockBuilder('CrudGenerator\View\View')
        ->disableOriginalConstructor()
        ->getMock();
        $output =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $output->expects($this->once())
        ->method('writeln');

        $sUT = new SandBoxStrategy($view, $output, $dialog);

        $sUT->ifDirDoesNotExistCreate('MyDir');
    }
}