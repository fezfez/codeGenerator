<?php
namespace CrudGenerator\Tests\General\View\Helpers\TemplateServiceContainerStrategies\PDOStrategy;

use CrudGenerator\View\Helpers\TemplateServiceContainerStrategies\PDOStrategy;
use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class GeneralTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzdzaz()
    {

        $sUT = new PDOStrategy;

        $dataObject = new Architect();
        $metadata = new MetadataDataObjectPDO(
        		new MetaDataColumnCollection(),
        		new MetaDataRelationCollection()
        );

        $metadata->setName('Myname');

        $column = new MetaDataColumn();
        $column->setName('Myname')
        ->setPrimaryKey(true);

        $metadata->appendColumn($column);

        $column = new MetaDataColumn();
        $column->setName('MyColumn')
        ->setPrimaryKey(false);

        $metadata->appendColumn($column);

        $dataObject->setMetadata($metadata);

        $this->assertInternalType(
            'string',
            $sUT->getClassName($dataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getFullClass($dataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getInjectionInDependencie($dataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getVariableName($dataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getCreateInstanceForUnitTest($dataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getFullClassForUnitTest($dataObject)
        );
    }
}
