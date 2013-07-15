<?php
namespace CrudGenerator\Tests\General\Generators\LoremIpsumGenerator;

use CrudGenerator\Generators\LoremIpsumGenerator;

class GetContentTest extends \PHPUnit_Framework_TestCase
{
    public function testHtml()
    {
        $suT = new LoremIpsumGenerator();

        $this->assertEquals(
            100,
            strlen($suT->getContent(100, 'html'))
        );
    }

    public function testTxt()
    {
        $suT = new LoremIpsumGenerator();

        $this->assertEquals(
            100,
            strlen($suT->getContent(100, 'txt'))
        );
    }

    public function testPlain()
    {
        $suT = new LoremIpsumGenerator();

        $this->assertEquals(
            100,
            strlen($suT->getContent(100, 'plain'))
        );
    }

    public function testEmpty()
    {
        $suT = new LoremIpsumGenerator();

        $this->assertEquals(
            0,
            strlen($suT->getContent(0))
        );
    }
}
