<?php
namespace CrudGenerator\View;

class ViewRenderer
{
    /**
     * @param string $path
     * @param string $templateName
     * @param array $datas
     */
    public function render($path, $templateName)
    {
        try {
            ob_start();
            include $path . $templateName;
            $content = ob_get_clean();
        } catch (\Exception $ex) {
            ob_end_clean();
            throw $ex;
        }

        return $content;
    }
}
