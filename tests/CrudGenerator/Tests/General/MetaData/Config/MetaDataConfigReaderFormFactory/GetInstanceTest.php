<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigReaderFormFactory;

use CrudGenerator\MetaData\Config\MetaDataConfigReaderFormFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $app =  $this->getMockBuilder('Silex\Application')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Config\MetaDataConfigReaderForm',
            MetaDataConfigReaderFormFactory::getInstance(
	            $app
	        )
        );
    }
}
