<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\DataObject;


/**
 * Represent relation between Metadata
 *
 * @author Stéphane Demonchaux
 */
class MetaDataRelationColumn
{
    const ONE_TO_MANY  = 3;
    const ONE_TO_ONE   = 4;
    const MANY_TO_MANY = 5;
    const MANY_TO_ONE  = 6;
    const UNKNOWN      = 7;

    /**
     * @var string Full name (ex: Test\My\Metadata)
     */
    private $fullName = null;
    /**
     * @var string Name
     */
    private $fieldName = null;
    /**
     * @var string Relation association type (ex manyToMany, oneToOne etc...)
     */
    private $associationType = null;
    /**
     * @var MetaData
     */
    private $metadata = null;

    /**
     * Set full name
     * @param  string                                                    $value
     * @return \CrudGenerator\Metadata\DataObject\MetaDataRelationColumn
     */
    public function setFullName($value)
    {
        $this->fullName = $value;

        return $this;
    }
    /**
     * Set name
     * @param  string                                                    $value
     * @return \CrudGenerator\Metadata\DataObject\MetaDataRelationColumn
     */
    public function setFieldName($value)
    {
        $this->fieldName = $value;

        return $this;
    }
    /**
     * Set association type
     * @param  string                                                    $value
     * @return \CrudGenerator\Metadata\DataObject\MetaDataRelationColumn
     */
    public function setAssociationType($value)
    {
        $this->associationType = $value;

        return $this;
    }
    /**
     * Set metadata
     * @param  MetaData                                                  $value
     * @return \CrudGenerator\Metadata\DataObject\MetaDataRelationColumn
     */
    public function setMetadata(MetaData $value)
    {
        $this->metadata = $value;

        return $this;
    }

    /**
     * Get full name
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }
    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        if (strrchr($this->fullName, '\\') === false) {
            return $this->fullName;
        } else {
            return str_replace('\\', '', strrchr($this->fullName, '\\'));
        }
    }
    /**
     * Get fields name
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
    /**
     * Get association type
     * @return string
     */
    public function getAssociationType()
    {
        return $this->associationType;
    }
    /**
     * Get fields name
     * @return MetaData
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
