<?php
namespace CrudGenerator\MetaData\DataObject;

use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationColumnDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;

abstract class MetaDataDataObject
{
    /**
     * @var MetaDataColumnDataObjectCollection
     */
    private $columnCollection = null;
    /**
     * @var MetaDataRelationDataObjectCollection
     */
    private $relationCollection = null;
    /**
     * @var array
     */
    private $identifier = array();

    public function __construct(
        MetaDataColumnDataObjectCollection $columnCollection,
        MetaDataRelationDataObjectCollection $relationCollection)
    {
        $this->columnCollection   = $columnCollection;
        $this->relationCollection = $relationCollection;
    }
    /**
     * @param MetaDataColumnDataObject $value
     */
    public function appendColumn(MetaDataColumnDataObject $value)
    {
        $this->columnCollection->append($value);
    }
    /**
     * @param MetaDataRelationColumnDataObject $value
     */
    public function appendRelation(MetaDataRelationColumnDataObject $value)
    {
        $this->relationCollection->append($value);
    }
    /**
     * @param MetaDataRelationColumnDataObject $value
     */
    public function addIdentifier($value)
    {
        $this->identifier[] = $value;
        return $this;
    }

    /**
     * @return MetaDataColumnDataObjectCollection
     */
    public function getColumnCollection()
    {
        return $this->columnCollection;
    }
    /**
     * @return MetaDataRelationColumnDataObject
     */
    public function getRelationCollection()
    {
        return $this->relationCollection;
    }
    /**
     * @return array
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
