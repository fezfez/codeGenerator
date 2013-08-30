<?php
namespace CrudGenerator\Tests\General\FileManagerStub;

use CrudGenerator\Utils\FileManagerStub;

class IsDirTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $stubDialog = $this->getMockBuilder('\Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileManagerStub($stubDialog, $stubOutput);
        $sUT->isDir('ttoto');
    }
}
