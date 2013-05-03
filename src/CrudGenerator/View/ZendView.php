<?php
namespace CrudGenerator\View;

use Zend\View\Model\ViewModel,
    Zend\View\Renderer\PhpRenderer,
    Zend\View\Resolver;

class ZendView
{
    private $zendView = null;

    public function __construct(ViewModel $zendView)
    {
        $this->zendView = $zendView;
    }

    public function render($path, $templateName, $datas)
    {
        $renderer = new PhpRenderer();

        $map = new Resolver\TemplateMapResolver(array(
              'tester' => $path . $templateName,
        ));

        $resolver = new Resolver\TemplateMapResolver($map);
        $renderer->setResolver($resolver);

        $this->zendView->setVariables($datas);
        $this->zendView->setTemplate('tester');

        return $renderer->render($this->zendView);
    }
}