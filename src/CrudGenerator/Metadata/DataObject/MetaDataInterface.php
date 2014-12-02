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

interface MetaDataInterface
{
    const ID    = 'id';
    const LABEL = 'label';
    const NAME  = 'name';

    /**
     * Append column dataobject
     * @param  MetaDataColumn $value
     * @return void
     */
    public function appendColumn(MetaDataColumn $value);
    /**
     * Append relation dataobject
     * @param  MetaDataRelationColumn $value
     * @return void
     */
    public function appendRelation(MetaDataRelationColumn $value);
    /**
     * Set name
     * @param  string   $value
     * @return MetaData
     */
    public function setName($value);
    /**
     * @param  string $name
     * @return mixed
     */
    public function getColumn($name);
    /**
     * @param  string $name
     * @return mixed
     */
    public function getRelation($name);
    /**
     * Get column collection
     * @return MetaDataColumnCollection
     */
    public function getColumnCollection($withoutIdentifier = false);
    /**
     * Get relation collection
     * @return MetaDataRelationCollection
     */
    public function getRelationCollection();
    /**
     * Get identifier
     * @return array
     */
    public function getIdentifier();
    /**
     * Get name
     * @return string
     */
    public function getName($ucfirst = false);

    /**
     * @return string
     */
    public function getOriginalName();
}
