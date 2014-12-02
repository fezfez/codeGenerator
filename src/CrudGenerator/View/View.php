<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\View;

/**
 * Manage template renderer
 *
 * @author Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 */
class View
{
    /**
     * @var ViewRenderer File interpreter
     */
    private $viewRenderer = null;

    /**
     * Manage template renderer
     *
     * @param ViewRenderer $viewRenderer
     */
    public function __construct(ViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * Render the template
     *
     * @param string $path
     * @param string $templateName
     * @param array  $datas
     */
    public function render($path, $templateName, array $datas)
    {
        $viewRenderer = clone $this->viewRenderer;
        foreach ($datas as $name => $data) {
            $viewRenderer->$name = $data;
        }

        return $viewRenderer->render($path, $templateName);
    }
}
