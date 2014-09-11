<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetaDataSourcesQuestion;

use CrudGenerator\Generators\Questions\MetaDataSourcesQuestionFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion',
            MetaDataSourcesQuestionFactory::getInstance($context)
        );
    }

    public function testInstanceWeb()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion',
            MetaDataSourcesQuestionFactory::getInstance($context)
        );
    }
}
