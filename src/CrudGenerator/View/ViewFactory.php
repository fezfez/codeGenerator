<?php
namespace CrudGenerator\View;

use CrudGenerator\View\View;
use CrudGenerator\View\ViewRenderer;
use CrudGenerator\FileManager;

/**
 * View manager factory
 *
 * @author Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 */
class ViewFactory
{
    /**
     * Build a View
     *
     * @param array $config
     * @return \CrudGenerator\View\ZendView
     */
    public static function getInstance()
    {
        $viewRenderer = new ViewRenderer();
        $fileManager  = new FileManager();

        return new View($fileManager, $viewRenderer);
    }
}
