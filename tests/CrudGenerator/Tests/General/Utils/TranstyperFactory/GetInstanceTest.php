<?php
namespace CrudGenerator\Tests\General\Util\TranstyperFactory;

use CrudGenerator\Utils\TranstyperFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testEncodeAndDecode()
    {
        $this->assertInstanceOf('CrudGenerator\Utils\Transtyper', TranstyperFactory::getInstance());
    }
}
