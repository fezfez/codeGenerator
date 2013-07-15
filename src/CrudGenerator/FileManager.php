<?php
namespace CrudGenerator;

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
        if (!@mkdir($pathname, 0777)) {
            throw new RuntimeException(sprintf("Could't create %s", $pathname));
        }
    }

    /**
     * Puts content into file
     * @param string $path File path
     * @param string $content File Content
     */
    public function filePutsContent($path, $content)
    {
        file_put_contents($path, $content);
        chmod($path, 0777);
    }

    /**
     * Get content from file
     * @param string $path File path
     */
    public function fileGetContent($path)
    {
        return file_get_contents($path);
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
}
