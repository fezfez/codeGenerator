<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Xml\XmlMetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\Xml\XmlMetaDataDAOFactory;
use CrudGenerator\Metadata\Driver\DriverConfig;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $config = XmlMetaDataDAOFactory::getDescription()->getDriversDescription()[0]->getConfig();
        $config->response('configUrl', __DIR__ . '/../data.xml');

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Xml\XmlMetaDataDAO',
            XmlMetaDataDAOFactory::getInstance($config)
        );
    }
}
