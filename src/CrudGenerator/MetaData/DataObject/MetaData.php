<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\DataObject;


/**
 * Base representation metadata for template generation
 *
 * @author Stéphane Demonchaux
 */
abstract class MetaData implements \JsonSerializable, MetaDataInterface
{
    /**
     * @var MetaDataColumnCollection Column collection
     */
    private $columnCollection = null;
    /**
     * @var MetaDataRelationCollection Relation collection
     */
    private $relationCollection = null;
    /**
     * @var string Name
     */
    private $name = null;

    /**
     * Base representation metadata for template generation
     * @param MetaDataColumnCollection   $columnCollection
     * @param MetaDataRelationCollection $relationCollection
     */
    public function __construct(
        MetaDataColumnCollection $columnCollection,
        MetaDataRelationCollection $relationCollection
    ) {
        $this->columnCollection   = $columnCollection;
        $this->relationCollection = $relationCollection;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::appendColumn()
     */
    public function appendColumn(MetaDataColumn $value)
    {
        $this->columnCollection->offsetSet($value->getName(), $value);
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::appendRelation()
     */
    public function appendRelation(MetaDataRelationColumn $value)
    {
        $this->relationCollection->offsetSet($value->getFullName(), $value);
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::setName()
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::getColumn()
     */
    public function getColumn($name)
    {
        return $this->columnCollection->offsetGet($name);
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::getRelation()
     */
    public function getRelation($name)
    {
        return $this->relationCollection->offsetGet($name);
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::getColumnCollection()
     */
    public function getColumnCollection($withoutIdentifier = false)
    {
        if ($withoutIdentifier === true) {
            $tmpColumnCollection = new MetaDataColumnCollection();

            foreach ($this->columnCollection as $column) {
                $isPk = $column->isPrimaryKey();
                if ($isPk === false) {
                    $tmpColumnCollection->append($column);
                }
            }

            return $tmpColumnCollection;
        } else {
            return $this->columnCollection;
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::getRelationCollection()
     */
    public function getRelationCollection()
    {
        return $this->relationCollection;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::getIdentifier()
     */
    public function getIdentifier()
    {
        $tmpColumnCollection = array();

        foreach ($this->columnCollection as $column) {
            $isPk = $column->isPrimaryKey();
            if ($isPk === true) {
                $tmpColumnCollection[] = $column;
            }
        }

        return $tmpColumnCollection;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::getName()
     */
    public function getName($ucfirst = false)
    {
        $name = $this->name;

        if (strrchr($name, '\\') !== false) {
            $name = str_replace('\\', '', strrchr($name, '\\'));
        }
        $name = $this->camelCase($name);
        if (true === $ucfirst) {
            return ucfirst($name);
        } else {
            return $name;
        }
    }

    /**
     * @param string $value
     */
    private function camelCase($value)
    {
        return preg_replace_callback(
            '/_(\w)/',
            function (array $matches) {
                return ucfirst($matches[1]);
            },
            $value
        );
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\DataObject\MetaDataInterface::getOriginalName()
     */
    public function getOriginalName()
    {
        return $this->name;
    }

    /* (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return array(
            self::ID    => $this->getOriginalName(),
            self::LABEL => $this->getOriginalName(),
            self::NAME  => $this->getOriginalName(),
        );
    }
}
