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

class StorageArray implements StorageInterface
{
    /**
     * @var array
     */
    private $store = array();

    /* (non-PHPdoc)
     * @see \CrudGenerator\Storage\StorageInterface::isValidStore()
     */
    public function isValidStore(array $args)
    {
        if (count($args) === 2) {
            return true;
        }

        return false;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Storage\StorageInterface::isValidAcces()
     */
    public function isValidAcces(array $args)
    {
        if (count($args) < 2) {
            return true;
        }

        return false;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Storage\StorageInterface::set()
     */
    public function set(array $args)
    {
        $this->store[$args[0]] = $args[1];
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Storage\StorageInterface::get()
     */
    public function get(array $args)
    {
        if (count($args) === 0) {
            return $this->store;
        } elseif (isset($this->store[$args[0]]) === true) {
            return $this->store[$args[0]];
        } else {
            return null;
        }
    }

    /* (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->store;
    }
}
