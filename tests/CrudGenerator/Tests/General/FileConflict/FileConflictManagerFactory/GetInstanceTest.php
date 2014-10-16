<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManagerFactory;

use CrudGenerator\FileConflict\FileConflictManagerFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\FileConflict\FileConflictManager',
            FileConflictManagerFactory::getInstance($context)
        );
    }
}
