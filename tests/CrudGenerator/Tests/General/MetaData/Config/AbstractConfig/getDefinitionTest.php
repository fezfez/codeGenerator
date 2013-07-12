<?php
namespace CrudGenerator\Tests\General\MetaData\Config\AbstractConfig;

use CrudGenerator\MetaData\PDO\PDOConfig;

class getDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new PDOConfig();

        $this->assertInternalType('string', $sUT->getDefinition());
    }
}

