<?php
namespace CrudGenerator\Generators;

class FileManager
{
    public function mkdir($pathname)
    {
        mkdir($pathname, 0777);
    }

    public function filePutsContent($path, $content)
    {
        file_put_contents($path, $content);
        chmod($path, 0777);
    }
}