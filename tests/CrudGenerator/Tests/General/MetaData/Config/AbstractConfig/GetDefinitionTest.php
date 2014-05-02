<?php
namespace CrudGenerator\Tests\General\MetaData\Config\AbstractConfig;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig;

class GetDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new PostgreSQLConfig();

        $this->assertInternalType('string', $sUT->getDefinition());
    }
}
