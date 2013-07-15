<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigReaderFactory;

use CrudGenerator\MetaData\Config\MetaDataConfigReaderFactory;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\DialogHelper;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Config\MetaDataConfigReader',
            MetaDataConfigReaderFactory::getInstance(
                new ConsoleOutput(),
                new DialogHelper()
            )
        );
    }
}
