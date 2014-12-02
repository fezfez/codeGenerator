<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\Json\JsonMetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $config = JsonMetaDataDAOFactory::getDescription()->getDriversDescription()[0]->getConfig();
        $config->response('configUrl', __DIR__ . '/../data.json');

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO',
            JsonMetaDataDAOFactory::getInstance($config)
        );
    }
}
