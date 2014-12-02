<?php
namespace CrudGenerator\Tests\General\Generators\Validator\GeneratorValidator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\Validator\GeneratorValidator;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\Sources\Doctrine2\MetadataDataObjectDoctrine2;

class IsValidTest extends \PHPUnit_Framework_TestCase
{
    public function testWithValidSchema()
    {
        $validator = $this->getMockBuilder('JsonSchema\Validator')
                          ->disableOriginalConstructor()
                          ->getMock();

        $validator->expects($this->once())
                  ->method('isValid')
                  ->will($this->returnValue(true));

        $schema = 'schema';
        $sUT    = new GeneratorValidator($schema, $validator);

        $sUT->isValid(array('my data'));
    }

    public function testWithInValidSchema()
    {
        $validator = $this->getMockBuilder('JsonSchema\Validator')
        ->disableOriginalConstructor()
        ->getMock();

        $validator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(false));

        $schema = 'schema';
        $sUT    = new GeneratorValidator($schema, $validator);

        $this->setExpectedException('InvalidArgumentException');

        $sUT->isValid(array('my data'));
    }

    public function testWithInValidMetadata()
    {
        $validator = $this->getMockBuilder('JsonSchema\Validator')
        ->disableOriginalConstructor()
        ->getMock();

        $validator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(true));

        $schema = 'schema';
        $sUT    = new GeneratorValidator($schema, $validator);

        $metadata = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(array()),
            new MetaDataRelationCollection(array())
        );

        $this->setExpectedException('InvalidArgumentException');

        $sUT->isValid(array('metadataTypeAccepted' => array('CrudGenerator\Metadata\DataObject\Fake')), $metadata);
    }

    public function testWithValidMetadata()
    {
        $validator = $this->getMockBuilder('JsonSchema\Validator')
        ->disableOriginalConstructor()
        ->getMock();

        $validator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(true));

        $schema = 'schema';
        $sUT    = new GeneratorValidator($schema, $validator);

        $metadata = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(array()),
            new MetaDataRelationCollection(array())
        );

        $sUT->isValid(
            array('metadataTypeAccepted' => array('CrudGenerator\Metadata\DataObject\MetaData')),
            $metadata
        );
    }
}
