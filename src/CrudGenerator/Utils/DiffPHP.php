<?php
/**
 * BUILT PROGRAMMATICALLY, SEE: https://svn.caedmon.net/svn/public/diff-php
 * PHP-Aware diff

 * Copyright 2008, Sean Coates
 *   Usage of the works is permitted provided that this instrument is retained
 *   with the works, so that any entity that uses the works is notified of this
 *   instrument.
 *   DISCLAIMER: THE WORKS ARE WITHOUT WARRANTY.
 * (Fair License - http://www.opensource.org/licenses/fair.php )
 * Short license: do whatever you like with this.
 */

namespace CrudGenerator\Utils;

use Exception;

/**
 * Generate php diff between two file
 *
 * @author Sean Coates
 */
class DiffPHP
{
    const DEBUG_SYNTAX = false; // set to true to get syntax error data (== broken diffs)

    const DIFF_PATH = '/usr/bin/diff';
    const DIFF_OPTS = '-u';

    /**
     * The magic happens here. Once instantiated, the entire
     * process runs
     * @param string $left
     * @param string $right
     * @throws Exception
     * @return string
     */
    public function diff($left = null, $right = null)
    {
        if (!is_readable($left)) {
            throw new Exception('left not readable' . $left);
        } elseif (!is_readable($right)) {
            throw new Exception('right not readable' . $right);
        }

        return $this->doDiff($left, $right);
    }

    /**
     * Calls the external diff program to get the base diff
     * @param string $left
     * @param string $right
     * @return string
     */
    private function doDiff($left, $right)
    {
        $diffCmd = self::DIFF_PATH . ' ' . self::DIFF_OPTS . " {$left} {$right}";
        return `$diffCmd`;
    }
}
