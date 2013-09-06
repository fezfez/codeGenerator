<?php
namespace CrudGenerator\Tests\General\View\Helpers\TemplateServiceContainerFactory;

use CrudGenerator\View\Helpers\TemplateServiceContainerFactory;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use Faker\Factory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceDoctrine2()
    {
        $metadata = $this->getMockBuilder('CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2')
        ->disableOriginalConstructor()
        ->getMock();
        $dataObject = $this->getMockForAbstractClass('CrudGenerator\DataObject');
        $dataObject->setMetaData($metadata);

        $this->assertInstanceOf(
            'CrudGenerator\View\Helpers\TemplateServiceContainer',
            TemplateServiceContainerFactory::getInstance($dataObject)
        );
    }

    public function testInstancePDO()
    {
        $metadata = $this->getMockBuilder('CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO')
        ->disableOriginalConstructor()
        ->getMock();
        $dataObject = $this->getMockForAbstractClass('CrudGenerator\DataObject');
        $dataObject->setMetaData($metadata);

        $this->assertInstanceOf(
            'CrudGenerator\View\Helpers\TemplateServiceContainer',
            TemplateServiceContainerFactory::getInstance($dataObject)
        );
    }
}
