<?php
namespace CrudGenerator\Generators\View;

use Zend\View\Model\ViewModel;

class ZendView
{
    private $zendView = null;

    public function __construct(ViewModel $zendView)
    {
        $this->zendView = $zendView;
    }

    public function render($path, $templateName, $datas)
    {
        $this->zendView->addScriptPath($path);
        $this->zendView->assign($datas);

        return $this->zendView->render($templateName);
    }
}