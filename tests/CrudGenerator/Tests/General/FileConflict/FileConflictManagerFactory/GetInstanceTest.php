<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManagerFactory;

use CrudGenerator\FileConflict\FileConflictManagerFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\FileConflict\FileConflictManager',
            FileConflictManagerFactory::getInstance($context)
        );
    }

    public function testInstanceWeb()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

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
