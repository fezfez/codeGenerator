<?php
namespace CrudGenerator\Tests\General\GeneratorsEmbed\ArchitectGenerator\MetadataToArrayFactory;

use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\MetadataToArrayFactory;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testWeb()
    {
    	$web =  $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new WebContext($web);

        $this->assertInstanceOf(
            'CrudGenerator\GeneratorsEmbed\ArchitectGenerator\MetadataToArrayWeb',
            MetadataToArrayFactory::getInstance($context)
        );
    }

    public function testCli()
    {
    	$ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
    	->disableOriginalConstructor()
    	->getMock();

    	$dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new CliContext($dialog, $ConsoleOutputStub);

        $this->assertInstanceOf(
            'CrudGenerator\GeneratorsEmbed\ArchitectGenerator\MetadataToArrayCli',
            MetadataToArrayFactory::getInstance($context)
        );
    }
}
