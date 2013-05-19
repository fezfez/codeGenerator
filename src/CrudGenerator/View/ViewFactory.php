<?php
namespace CrudGenerator\View;

use CrudGenerator\View\View;
use CrudGenerator\View\ViewRenderer;
use CrudGenerator\FileManager;

class ViewFactory
{
    private function __construct()
    {

    }

    /**
     * @param array $config
     * @return \CrudGenerator\View\ZendView
     */
    public static function getInstance()
    {
        $viewRenderer = new ViewRenderer();
        $fileManager = new FileManager();
        return new View($fileManager, $viewRenderer);
    }
}
