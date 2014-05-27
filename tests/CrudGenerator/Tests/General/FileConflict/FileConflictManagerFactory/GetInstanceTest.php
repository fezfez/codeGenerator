<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManagerFactory;

use CrudGenerator\FileConflict\FileConflictManagerFactory;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $ConsoleOutputStub);

        $this->assertInstanceOf(
            'CrudGenerator\FileConflict\FileConflictManager',
            FileConflictManagerFactory::getInstance($context)
        );
    }

    public function testInstanceWeb()
    {
        $web =  $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new WebContext($web);

        $this->assertInstanceOf(
            'CrudGenerator\FileConflict\FileConflictManager',
            FileConflictManagerFactory::getInstance($context)
        );
    }

    public function testInstanceErrror()
    {
        $context = $this->getMock('CrudGenerator\Context\ContextInterface');

        $this->setExpectedException('InvalidArgumentException');

        FileConflictManagerFactory::getInstance($context);
    }
}