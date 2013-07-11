<?php
namespace CrudGenerator\Tests\General\Service\CliFactory;

use CrudGenerator\Service\CliFactory;

class getInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'Symfony\Component\Console\Application',
             CliFactory::getInstance()
        );
    }
}

