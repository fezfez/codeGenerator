<?php
namespace CrudGenerator\Tests\General\FileManagerStub;

use CrudGenerator\Utils\FileManagerStub;

class MkdirTest extends \PHPUnit_Framework_TestCase
{

    public function testOk()
    {
        $stubDialog = $this->getMockBuilder('\Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileManagerStub($stubDialog, $stubOutput);

        $sUT->mkdir(__DIR__);
    }
}
