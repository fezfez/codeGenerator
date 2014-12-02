<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources;

/**
 * Metadata config interface
 *
 * @author Stéphane Demonchaux
 */
interface MetaDataConfigInterface
{
    /**
     * @return array
     */
    public function jsonSerialize();
}
