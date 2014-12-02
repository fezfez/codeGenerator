<?php
namespace CrudGenerator\Tests\General\Adapater\AdapaterFinderFactory;

use CrudGenerator\Metadata\MetaDataSourceFinderFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\Metadata\MetaDataSourceFinder',
            MetaDataSourceFinderFactory::getInstance()
        );
    }
}
