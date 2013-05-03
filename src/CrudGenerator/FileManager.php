<?php
namespace CrudGenerator;

use RuntimeException;

class FileManager
{
    public function mkdir($pathname)
    {
        if(!mkdir($pathname, 0777)) {
            throw new RuntimeException(sprintf("Could't create %s", $pathname));
        }
    }

    public function filePutsContent($path, $content)
    {
        file_put_contents($path, $content);
        chmod($path, 0777);
    }
}