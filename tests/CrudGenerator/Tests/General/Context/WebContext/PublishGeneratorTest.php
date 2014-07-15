<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;

class PublishGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $application = $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $myTmpObject = new \ArrayObject();
        $myTmpObject->request = $request;

        $application->expects($this->once())
        ->method('offsetGet')
        ->with('request')
        ->will($this->returnValue($myTmpObject));

        $sUT            = new WebContext($application);
        $metadataSource = new MetaDataSource();
        $dto            = new Architect();
        $metadata       = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $metadata->setName('test');
        $dto->setMetadata($metadata);

        $generator = new GeneratorDataObject();
        $generator->setDTO($dto)
                  ->setMetadataSource($metadataSource)
                  ->setName('test');

        $sUT->publishGenerator($generator);
    }
}
