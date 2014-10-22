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

class StorageArray implements \JsonSerializable
{
    private $store = array();

    /**
     * @param array $args
     * @return boolean
     */
    public function isValidStore(array $args)
    {
        if (count($args) === 2) {
            return true;
        }

        return false;
    }

    /**
     * @param array $args
     * @return boolean
     */
    public function isValidAcces(array $args)
    {
        if (count($args) < 2) {
            return true;
        }

        return false;
    }

    /**
     * @param array $args
     */
    public function set(array $args)
    {
        $this->store[$args[0]] = $args[1];
    }

    /**
     * @param array $args
     * @return mixed
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

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->store;
    }
}
