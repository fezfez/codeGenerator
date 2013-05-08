<?php

namespace CrudGenerator\Service;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CliFactory implements FactoryInterface
{
    /**
     * @var \Zend\EventManager\EventManagerInterface
     */
    protected $events;

    /**
     * @var \Symfony\Component\Console\Helper\HelperSet
     */
    protected $helperSet;

    /**
     * @var array
     */
    protected $commands = array();

    /**
     * @param  ServiceLocatorInterface $sm
     * @return \Zend\EventManager\EventManagerInterface
     */
    public function getEventManager(ServiceLocatorInterface $sm)
    {
        if (null === $this->events) {
            /* @var $events \Zend\EventManager\EventManagerInterface */
            $events = $sm->get('EventManager');
            $events->addIdentifiers(array(
                __CLASS__,
                'crudgenerator'
            ));

            $this->events = $events;
        }
    
        return $this->events;
    }
    
    /**
     * {@inheritDoc}
     * @return Application
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $cli = new Application;
        $cli->setName('CrudGenerator Command Line Interface');
        $cli->setHelperSet(new HelperSet);

        // Load commands using event
        $this->getEventManager($sl)->trigger('loadCli.post', $cli, array('ServiceManager' => $sl));

        return $cli;
    }
}