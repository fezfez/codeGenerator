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

interface StorageInterface extends \JsonSerializable
{
    /**
     * @param  array   $args
     * @return boolean
     */
    public function isValidStore(array $args);
    /**
     * @param  array   $args
     * @return boolean
     */
    public function isValidAcces(array $args);
    /**
     * @param  array $args
     * @return void
     */
    public function set(array $args);
    /**
     * @param  array $args
     * @return mixed
     */
    public function get(array $args);
};
