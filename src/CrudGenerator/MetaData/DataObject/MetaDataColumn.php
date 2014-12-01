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
 * Metadata column representation
 *
 * @author Stéphane Demonchaux
 */
class MetaDataColumn
{
    /**
     * @var string Column name
     */
    private $name = null;
    /**
     * @var string Column type
     */
    private $type = null;
    /**
     * @var integer Column length
     */
    private $length = null;
    /**
     * @var boolean Column is nullable
     */
    private $nullable = true;
    /**
     * @var boolean Is column is a primary key
     */
    private $primaryKey = false;

    /**
     * Set Column name
     *
     * @param  string                                            $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataColumn
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }
    /**
     * Set Column type
     *
     * @param  string                                            $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataColumn
     */
    public function setType($value)
    {
        $this->type = $value;

        return $this;
    }
    /**
     * Set Column length
     *
     * @param  integer                                           $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataColumn
     */
    public function setLength($value)
    {
        $this->length = $value;

        return $this;
    }
    /**
     * Set Column is nullable
     *
     * @param  boolean                                           $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataColumn
     */
    public function setNullable($value)
    {
        $this->nullable = $value;

        return $this;
    }
    /**
     * Set Column is a primary key
     *
     * @param  boolean                                           $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataColumn
     */
    public function setPrimaryKey($value)
    {
        $this->primaryKey = $value;

        return $this;
    }

    /**
     * Get Column name
     *
     * @return string
     */
    public function getName($ucfirst = false)
    {
        $value = $this->camelCase($this->name);
        if (true === $ucfirst) {
            return ucfirst($value);
        } else {
            return $value;
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

    /**
     * @return string
     */
    public function getOrininalName()
    {
        return $this->name;
    }
    /**
     * Get Column type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Get Column length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }
    /**
     * Get Column is nullable
     *
     * @return boolean
     */
    public function getNullable()
    {
        return $this->nullable;
    }
    /**
     * Get column is a primary key
     *
     * @return boolean
     */
    public function isPrimaryKey()
    {
        return $this->primaryKey;
    }
}
