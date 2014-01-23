<?php
namespace CrudGenerator\Tests\General\View\Helpers\TemplateDatabaseConnectorStrategies\PDOStrategy;

use CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\PDOStrategy;
use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class GeneralTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzdzaz()
    {
        $crudDataObject = new Architect();
        $dataObject = new MetadataDataObjectPDO(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $dataObject->setName('Myname');

        $column = new MetaDataColumn();
        $column->setName('Myname')
               ->setPrimaryKey(true);

        $dataObject->appendColumn($column);

        $column = new MetaDataColumn();
        $column->setName('MyColumn')
               ->setPrimaryKey(false);

        $dataObject->appendColumn($column);

        $crudDataObject->setMetadata($dataObject);

        $sUT = new PDOStrategy;

        $this->assertInternalType(
            'string',
            $sUT->getClassName($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getCreateInstance($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getFullClass($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getModifyQuery($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getPersistQuery($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getQueryFindAll($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getQueryFindOneBy($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getRemoveQuery($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getVariableName($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getPurgeQueryForUnitTest($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getTypeReturnedByDatabase($crudDataObject)
        );

        $this->assertInternalType(
            'string',
            $sUT->getConcreteTypeReturnedByDatabase($crudDataObject)
        );
    }
}
