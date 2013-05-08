<?php
namespace CrudGenerator;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\EventManager\EventInterface;
use CrudGenerator\Command\CreateCommand;
use CrudGenerator\Command\UpToDateCommand;
use CrudGenerator\Doctrine\Helper\ServiceManagerHelper;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

class Module implements
    AutoloaderProviderInterface,
    ServiceProviderInterface,
    BootstrapListenerInterface
{

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            AutoloaderFactory::STANDARD_AUTOLOADER => array(
                StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__,
                    'Generators' => __DIR__ . 'Generators/'
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return array(
                'factories' => array(
                'crudgenerator.cli' => 'CrudGenerator\Service\CliFactory',
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        /* @var $app \Zend\Mvc\ApplicationInterface */
        $app    = $e->getTarget();
        $events = $app->getEventManager()->getSharedManager();

        // Attach to helper set event and load the entity manager helper.
        $events->attach(
            'crudgenerator',
            'loadCli.post',
            function (EventInterface $e) {
                /* @var $cli \Symfony\Component\Console\Application */
                $cli = $e->getTarget();

                $cli->addCommands(
                    array(
                        new CreateCommand(),
                        new UpToDateCommand(),
                    )
                );

                /* @var $sm ServiceLocatorInterface */
                $sm = $e->getParam('ServiceManager');
                /* @var $em \Doctrine\ORM\EntityManager */
                $em = $sm->get('doctrine.entitymanager.orm_default');
                $helperSet = $cli->getHelperSet();
                $helperSet->set(new DialogHelper(), 'dialog');
                $helperSet->set(new FormatterHelper(), 'formatter');
                $helperSet->set(new ConnectionHelper($em->getConnection()), 'db');
                $helperSet->set(new EntityManagerHelper($em), 'em');
                $helperSet->set(new ServiceManagerHelper($sm), 'sm');
            }
        );
    }
}
