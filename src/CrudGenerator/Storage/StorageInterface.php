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

interface StorageInterface
{
    /**
     * @param  array   $args
     * @param  string  $methodName
     * @return boolean
     */
    public function isValidStore(array $args, $methodName);
    /**
     * @param  array   $args
     * @param  string  $methodName
     * @return boolean
     */
    public function isValidAccess(array $args, $methodName);
    /**
     * @param array $args
     */
    public function set(array $args);
    /**
     * @param  array $args
     * @return mixed
     */
    public function get(array $args);

    public function jsonSerialize();
};
