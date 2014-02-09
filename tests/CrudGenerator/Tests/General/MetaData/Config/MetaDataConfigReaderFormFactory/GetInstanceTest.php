<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigReaderFormFactory;

use CrudGenerator\MetaData\Config\MetaDataConfigReaderFormFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Config\MetaDataConfigReaderForm',
            MetaDataConfigReaderFormFactory::getInstance()
        );
    }
}
