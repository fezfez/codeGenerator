<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigReaderFactory;

use CrudGenerator\MetaData\Config\MetaDataConfigReaderFactory;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\DialogHelper;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();

        $stubDialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
                            ->disableOriginalConstructor()
                            ->getMock();


        $sUT = MetaDataConfigReaderFactory::getInstance(
            $stubOutput,
            $stubDialog
        );

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Config\MetaDataConfigReader',
            $sUT
        );
    }
}
