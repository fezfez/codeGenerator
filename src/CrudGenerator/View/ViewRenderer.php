<?php
namespace CrudGenerator\View;

use CrudGenerator\View\ViewRendererException;

/**
 * Template renderer
 *
 * @author Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 */
class ViewRenderer
{
    /**
     * Interprete the file
     *
     * @param string $path
     * @param string $templateName
     * @throws Exception
     * @return string
     */
    public function render($path, $templateName)
    {
        try {
            ob_start();
            include $path . $templateName;
            $content = ob_get_clean();
        } catch (\Exception $ex) {
            ob_end_clean();
            throw new ViewRendererException('In : "' . $path . $templateName . '" ' . $ex->getMessage() . ' Line ' . $ex->getLine());
        }

        return $content;
    }
}
