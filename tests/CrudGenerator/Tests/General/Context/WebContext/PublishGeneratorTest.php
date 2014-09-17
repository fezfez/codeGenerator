<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\DataObject;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;

class PublishGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $request        = new \Symfony\Component\HttpFoundation\Request();
        $sUT            = new WebContext($request);
        $metadataSource = new MetaDataSource();
        $dto            = new DataObject();
        $metadata       = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $metadata->setName('test');
        $dto->setMetadata($metadata);

        $generator = new GeneratorDataObject();
        $generator->setDto($dto)
                  ->setMetadataSource($metadataSource)
                  ->setName('test');

        $sUT->publishGenerator($generator);
    }
}
