<?php
namespace CrudGenerator\Tests\General\MetaData\Config\AbstractConfig;

use CrudGenerator\MetaData\Sources\PDO\PDOConfig;

class GetDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new PDOConfig();

        $this->assertInternalType('string', $sUT->getDefinition());
    }
}
