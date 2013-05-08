<?php
namespace CrudGenerator\View;

use CrudGenerator\View\ZendView;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

class ZendViewFactory
{
    private function __construct()
    {

    }

    /**
     * @param array $config
     * @return \CrudGenerator\View\ZendView
     */
    public static function getInstance(array $config = array())
    {
        $zendView = new ViewModel($config);
        return new ZendView($zendView, new PhpRenderer());
    }
}
