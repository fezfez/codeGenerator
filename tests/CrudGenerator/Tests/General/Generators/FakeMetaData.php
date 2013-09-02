<?php

use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;


$metadata = new MetadataDataObjectDoctrine2(
    new MetaDataColumnCollection(),
    new MetaDataRelationCollection()
);

$column = new MetaDataColumn();
$column->setName('id')
->setNullable(true)
->setType('integer')
->setLength('100')
->setPrimaryKey(true);

$metadata->appendColumn($column);
$metadata->setName('toto');

$column = new MetaDataColumn();
$column->setName('tetze')
->setNullable(true)
->setType('integer')
->setLength('100');

$column = new MetaDataColumn();
$column->setName('myDate')
->setNullable(true)
->setType('date')
->setLength('100');

$metadata->appendColumn($column);

return $metadata;