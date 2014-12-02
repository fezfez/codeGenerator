<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Storage;

class StorageString implements StorageInterface
{
    private $store = null;

    /* (non-PHPdoc)
     * @see \CrudGenerator\Storage\StorageInterface::isValidStore()
     */
    public function isValidStore(array $args)
    {
        if (count($args) === 1) {
            return true;
        }

        return false;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Storage\StorageInterface::isValidAcces()
     */
    public function isValidAcces(array $args)
    {
        if (count($args) === 0) {
            return true;
        }

        return false;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Storage\StorageInterface::set()
     */
    public function set(array $args)
    {
        $this->store = $args[0];
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Storage\StorageInterface::get()
     */
    public function get(array $args)
    {
        return $this->store;
    }

    /* (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->store;
    }
}
