<?php
namespace CrudGenerator;

use RuntimeException;

class FileManager
{
    /**
     * @param string $pathname
     * @throws RuntimeException
     */
    public function mkdir($pathname)
    {
        if (!@mkdir($pathname, 0777)) {
            throw new RuntimeException(sprintf("Could't create %s", $pathname));
        }
    }

    /**
     * @param string $path
     * @param string $content
     */
    public function filePutsContent($path, $content)
    {
        file_put_contents($path, $content);
        chmod($path, 0777);
    }

    /**
     * @param string $path
     */
    public function fileGetContent($path)
    {
        return file_get_contents($path);
    }

    /**
     * @param string $path
     */
    public function isFile($path)
    {
        return is_file($path);
    }

    /**
     * @param string $file
     */
    public function fileExists($file)
    {
        return file_exists($file);
    }

    /**
     * @param string $file
     */
    public function includeFile($file)
    {
        return include $file;
    }
}
