<?php
namespace CrudGenerator\Tests\General\Adapater\AdapaterFinderFactory;

use CrudGenerator\MetaData\MetaDataSourceFinderFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSourceFinder',
            MetaDataSourceFinderFactory::getInstance()
        );
    }
}
