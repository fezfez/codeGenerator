<?php
namespace CrudGenerator\Tests\General\Util\Transtyper;

use CrudGenerator\Utils\Transtyper;

class EncodeTest extends \PHPUnit_Framework_TestCase
{
    public function testEncodeAndDecode()
    {
        $sUT = new Transtyper();

        $originalString = 'Im a super inefficiant test !';

        $string = $sUT->encode($originalString);
        $string = $sUT->decode($string);

        $this->assertEquals($originalString, $string);
    }
}
