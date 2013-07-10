<?php
namespace CrudGenerator\View;

use CrudGenerator\FileManager;
use CrudGenerator\View\ViewRenderer;

/**
 * Manage template renderer
 *
 * @author Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 */
class View
{
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;
    /**
     * @var ViewRenderer File interpreter
     */
    private $viewRenderer = null;

    /**
     * @param FileManager $fileManager
     * @param ViewRenderer $viewRenderer
     */
    public function __construct(FileManager $fileManager, ViewRenderer $viewRenderer)
    {
        $this->fileManager  = $fileManager;
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * Render the template
     *
     * @param string $path
     * @param string $templateName
     * @param array $datas
     */
    public function render($path, $templateName, array $datas)
    {
        $viewRenderer = clone $this->viewRenderer;
        foreach($datas as $name => $data) {
            $viewRenderer->$name = $data;
        }

        return $viewRenderer->render($path, $templateName);
    }
}
