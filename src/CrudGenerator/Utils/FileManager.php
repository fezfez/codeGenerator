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

use RuntimeException;

/**
 * Manage file and directory
 *
 * @author Stéphane Demonchaux
 */
class FileManager
{
    /**
     * Create a directory
     * @param string $pathname Dir to create
     * @throws RuntimeException
     */
    public function mkdir($pathname)
    {
        if (@mkdir($pathname, 0777) === false) {
            throw new RuntimeException(sprintf("Could't create %s", realpath(getcwd()) . $pathname));
        }
    }

    /**
     * Puts content into file
     * @param string $path File path
     * @param string $content File Content
     */
    public function filePutsContent($path, $content)
    {
        if (@file_put_contents($path, $content) === false) {
            throw new \RuntimeException(sprintf("Could't puts content %s", $path));
        }

        chmod($path, 0777);
    }

    /**
     * Get content from file
     * @param string $path File path
     */
    public function fileGetContent($path)
    {
        $return = @file_get_contents($path);
        if ($return === false) {
            throw new \RuntimeException(sprintf("Could't load content %s", $path));
        }
        return $return;
    }

    /**
     * Test if is file
     * @param string $path File path
     */
    public function isFile($path)
    {
        return is_file($path);
    }

    /**
     * Test if is directory
     * @param string $path Directory path
     */
    public function isDir($path)
    {
        return is_dir($path);
    }

    /**
     * Test if file exist
     * @param string $file File path
     */
    public function fileExists($file)
    {
        return file_exists($file);
    }

    /**
     * Include file
     * @param string $file File path
     */
    public function includeFile($file)
    {
        return include $file;
    }

    /**
     * Delete file
     * @param string $file File path
     */
    public function unlink($file)
    {
        if (@unlink($file) === false) {
            throw new \RuntimeException(sprintf("Could't delete %s", $file));
        }
    }

    /**
     * Glob dir
     * @param string $dir Dir path
     */
    public function glob($dir, $params = null)
    {
        return glob($dir, $params);
    }

    /**
     * Create dir if not exist
     * @param string $directory
     * @return boolean
     */
    public function ifDirDoesNotExistCreate($directory)
    {
        if ($this->isDir($directory) === false) {
            $this->mkdir($directory);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $regex
     * @param string|null $directory
     * @return \RegexIterator
     */
    public function searchFileByRegex($regex, $directory = null)
    {
        if ($directory === null) {
            $directory = getcwd();
        }

        return new \RegexIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY,
                \RecursiveIteratorIterator::CATCH_GET_CHILD
            ),
            $regex,
            \RecursiveRegexIterator::GET_MATCH
        );
    }
}
