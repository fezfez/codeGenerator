<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Utils;

/**
 * Transtyper
 */
class Transtyper
{
    /**
     * @param string $string
     *
     * @return string A PHP value
     */
    public function encode($string)
    {
        return json_encode($string, JSON_PRETTY_PRINT);
    }

    /**
     * @param string $string
     *
     * @return mixed A PHP value
     */
    public function decode($string, $assoc = true)
    {
        return json_decode($string, $assoc);
    }
}
