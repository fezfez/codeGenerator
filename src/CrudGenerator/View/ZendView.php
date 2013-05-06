<?php
namespace CrudGenerator\View;

use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class ZendView
{
    private $zendView = null;
    private $phprenderer = null;

    public function __construct(ViewModel $zendView, PhpRenderer $phprenderer)
    {
        $this->zendView    = $zendView;
        $this->phprenderer = $phprenderer;
    }

    public function render($path, $templateName, $datas)
    {
        $map = new Resolver\TemplateMapResolver(array(
              $templateName => $path . $templateName,
        ));

        $resolver = new Resolver\TemplateMapResolver($map);
        $this->phprenderer->setResolver($resolver);

        $this->zendView->setVariables($datas);
        $this->zendView->setTemplate($templateName);

        return $this->phprenderer->render($this->zendView);
    }
}