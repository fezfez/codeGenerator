<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\EnvironnementParser;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\EnvironnementParser;
use Symfony\Component\Yaml\Yaml;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testEnvironnementMalFormed()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $sUT = new EnvironnementParser($context);

        $string = 'environnement :
    framework :
        "zend_framework_2"';

        $process = Yaml::parse($string, true);

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testEnvironnementfzefzefzef()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();
        $context->expects($this->exactly(3))
        ->method('askCollection')
        ->will($this->onConsecutiveCalls('zend_framework_2', 'pdo'));
        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $sUT = new EnvironnementParser($context);

        $string = 'environnement :
    framework :
        "zend_framework_2" :
            backend : [pdo, doctrine2]
            template : [phtml]
        symfony2 :
            backend : [doctrine2]
            template : [twig]';

        $process = Yaml::parse($string, true);

        $generator = $sUT->evaluate($process, $phpParser, $generator, true);

        $this->assertEquals(
            array('framework' => 'zend_framework_2', 'backend' => 'pdo'),
            $generator->getEnvironnementCollection()
        );
    }
}
