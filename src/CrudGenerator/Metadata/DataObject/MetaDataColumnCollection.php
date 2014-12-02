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

use ArrayObject;

/**
 * Metadata column collection
 *
 * @author Stéphane Demonchaux
 */
class MetaDataColumnCollection extends ArrayObject
{
    /**
     * @return MetaDataColumn
     */
    public function end()
    {
        $columns = array_values($this->getArrayCopy());

        return array_pop($columns);
    }

    /**
     * @return MetaDataColumn
     */
    public function first()
    {
        $columns = array_values($this->getArrayCopy());

        return $columns[0];
    }
}
