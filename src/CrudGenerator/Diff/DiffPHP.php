<?php
//BUILT PROGRAMMATICALLY, SEE: https://svn.caedmon.net/svn/public/diff-php
/// PHP-Aware diff

/// Copyright 2008, Sean Coates
///   Usage of the works is permitted provided that this instrument is retained
///   with the works, so that any entity that uses the works is notified of this
///   instrument.
///   DISCLAIMER: THE WORKS ARE WITHOUT WARRANTY.
/// (Fair License - http://www.opensource.org/licenses/fair.php )
/// Short license: do whatever you like with this.

namespace CrudGenerator;

use Exception;

class DiffPHP {

    const DEBUG_SYNTAX = false; // set to true to get syntax error data (== broken diffs)

    const DIFF_PATH = '/usr/bin/diff';
    const DIFF_OPTS = '-u';

    /**
     * The "left" file, as passed by svn (or cli)
     */
    protected $left;

    /**
     * The "right" file, as passed by svn (or cli)
     */
    protected $right;

    /**
     * A "nice" version of the left file.
     *
     * Instead of foo/bar/.svn/base/whatever.php, it would just be whatever.php
     */
    protected $niceLeft;

    /**
     * A "nice" version of the right file.
     *
     * Instead of foo/bar/.svn/base/whatever.php, it would just be whatever.php
     */
    protected $niceRight;

    /**
     * Captured file contents (prevents reading the file twice + diff)
     */
    protected $fileContents;

    /**
     * The output from the diff executable
     */
    protected $diff;

    /**
     * Each chunk of the diff goes in here (begins with a @@ identifier line)
     */
    protected $chunks;

    /**
     * Parsed version of the left file (parsekit parsed)
     */
    protected $parsedLeft = null;

    /**
     * Constructor. The magic happens here. Once instantiated, the entire
     * process runs
     */
    public function __construct($left=null, $right=null)
    {
        if (!is_readable($left)) {
            throw new Exception('left not readable' . $left);
        } elseif(!is_readable($right)) {
            throw new Exception('right not readable' . $right);
        } else {
            $this->left = $left;
            $this->right = $right;
        }

        $this->fileContents = file_get_contents($this->left);

        $this->doDiff();
        
        echo $this->diff;exit;

        // subject (probably) IS a PHP file:
        if (!isset($_ENV['NODIFFPHP']) && stripos($this->fileContents, '<?') !== false) {
            $this->splitDiff();
            $this->determineHierarchy();
            $this->reconstructDiff();
        } else {
            // not a PHP file; return regular diff:
            echo $this->diff;
        }
    }

    /**
     * Parses the passed arguments.
     *
     * Determines if it's svn (7 args) or cli (2 args), and stores the parsed
     * arguments.
     */
    protected function parseArgs() {
        // if this is being called from svn, we'll get 4 arguments
        //   (8th is argv 0 == this script)
        var_dump($_SERVER['argc']);exit;
        if (8 == $_SERVER['argc']) {
            $this->niceLeft = $_SERVER['argv'][3];
            $this->niceRight = $_SERVER['argv'][5];
            $this->left = $_SERVER['argv'][6];
            $this->right = $_SERVER['argv'][7];
        } else if (3 == $_SERVER['argc']) {
            // 2 arguments means a regular diff
            $this->niceLeft = $_SERVER['argv'][1];
            $this->niceRight = $_SERVER['argv'][2];
            $this->left = $this->niceLeft;
            $this->right = $this->niceRight;
        } else {
            echo DIFFPHP_INFO . "\n";
            die();
        }
    }

    /**
     * Calls the external diff program to get the base diff
     */
    protected function doDiff() {
        if (is_readable($this->left) && is_readable($this->right)) {
            $diffCmd = self::DIFF_PATH . ' ' . self::DIFF_OPTS . " {$this->left} {$this->right}";
            $this->diff = `$diffCmd`;
        } else {
            die("{$this->left} or {$this->right} is not readable\n");
        }
    }

    /**
     * Takes an identifier line (looks like: @@ -30,23 +30,79 @@) and returns
     * the begin line number
     */
    protected function parseLineNum($identifier) {
        list(,$from) = explode(" ", $identifier);
        list($from) = explode(',', $from);
        return (int) substr($from, 1);
    }

    /**
     * Sanitizes CRLF or CR into just LF
     */
    protected function sanitizeLineEndings($data) {
        // first, sanitize line endings:
        $data = str_replace("\r\n", "\n", $data);
        $data = str_replace("\r",   "\n", $data);
        return $data;
    }

    /**
     * Actually splits the diff into chunks and stores chunks + line numbers
     */
    protected function splitDiff() {
        // now split:
        $this->diff = explode("\n", $this->sanitizeLineEndings($this->diff));

        // array to return:
        $this->chunks = array();

        // line counter
        $line = 0;

        // outer loop: file(s)
        $maxLine = count($this->diff);

        // skip first 2 lines as left, right files
        $line += 2;

        // descend into data chunks
        while ($line < $maxLine) {
            // next line is the chunk identifier
            $dataChunk = array();
            $dataChunk['identifier'] = $this->diff[$line++];
            $dataChunk['line'] = $this->parseLineNum($dataChunk['identifier']);
            $dataChunk['data'] = array();
            while ($line < $maxLine && !(substr($this->diff[$line], 0, 2) == '@@' && substr($this->diff[$line], -2) == '@@')) {
                $dataChunk['data'][] = $this->diff[$line++];
            }
            $this->chunks[] = $dataChunk;
        }
    }

    /**
     * Reconstructs the diff (with adjusted identifier lines, and outputs the
     * result)
     */
    protected function reconstructDiff() {
        $out = "--- {$this->niceLeft}\n+++ {$this->niceRight}\n";
        foreach ($this->chunks as $chunk) {
            $out .= $chunk['identifier'] . "\n";
            $out .= implode("\n", $chunk['data']) ."\n";
        }
        echo $out;
    }

    /**
     * Matches the chunk map to the line map
     */
    protected function determineHierarchy() {

        for ($chunknum=0; $chunknum < count($this->chunks); $chunknum++) {
            $this->chunks[$chunknum]['identifier'] .= $this->getIdentifier($this->chunks[$chunknum]['line']);
        }
    }

    /**
     * Gets the identifier at a given line, from parsekit
     *
     * @param int $line the line number
     * @return string class::function on success, null on fail
     */
    protected function getIdentifier($line)
    {
        if ($this->parsedLeft === null) {
            $this->parsedLeft = parsekit_compile_file($this->left);
        }

        // check for class first:
        if (isset($this->parsedLeft['class_table'])) {
            foreach ($this->parsedLeft['class_table'] as $class => $ctable) {
                if (!($line >= $ctable['line_start'] && $line <= $ctable['line_end'])) {
                    continue; // not in this class
                }
                if (isset($ctable['function_table'])) {
                    foreach ($ctable['function_table'] as $function => $ftable) {
                        if ($line >= $ftable['line_start'] && $line <= $ftable['line_end']){
                            return " {$class}::{$function}()";
                        }
                    }
                }
                return " {$class} (class)";
            }
        }

        // now check for a function:
        if (isset($this->parsedLeft['function_table'])) {
            foreach ($this->parsedLeft['function_table'] as $function => $ftable) {
                if ($line >= $ftable['line_start'] && $line <= $ftable['line_end']){
                    return " {$function}()";
                }
            }
        }

        // not found, so return null
        return null;
    }
}
