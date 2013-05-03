<?php
namespace CrudGenerator\Generators\View;

use CrudGenerator\Generators\View\ZendView;
use Zend\View\Model\ViewModel;

class ZendViewFactory
{
    private function __construct()
    {
        
    }

    public static function getInstance(array $config = array())
    {
        $zendView = new ViewModel($config);
        return new ZendView($zendView);
    }
}