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

class StorageString implements \JsonSerializable
{
    private $store = null;

    /**
     * @param array $args
     * @return boolean
     */
    public function isValidStore(array $args)
    {
        if (count($args) === 1) {
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
        if (count($args) === 0) {
            return true;
        }

        return false;
    }

    /**
     * @param array $args
     */
    public function set(array $args)
    {
        $this->store = $args[0];
    }
    /**
     * @param array $args
     * @return mixed
     */
    public function get(array $args)
    {
        return $this->store;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->store;
    }
}
