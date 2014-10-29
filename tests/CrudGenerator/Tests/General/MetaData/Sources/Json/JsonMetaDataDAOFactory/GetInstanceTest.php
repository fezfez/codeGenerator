<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json\JsonMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory;
use CrudGenerator\MetaData\Driver\DriverConfig;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $config = JsonMetaDataDAOFactory::getDescription()->getDriversDescription()[0]->getConfig();
        $config->response('configUrl', __DIR__ . '/../data.json');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO',
            JsonMetaDataDAOFactory::getInstance($config)
        );
    }
}
