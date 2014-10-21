<?php
namespace CrudGenerator\Tests\General\Metadata\Driver\Pdo\PdoDriver;

use CrudGenerator\Metadata\Driver\Pdo\PdoDriver;
use CrudGenerator\MetaData\Driver\DriverConfig;

class IsValidTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalid()
    {
        $sUT    = new PdoDriver();
        $config = new DriverConfig('im a name');
        $config->response('configHost', 'tutu')
               ->response('configDatabaseName', 'toto')
               ->response('configUser', 'titi')
               ->response('configPassword', 'trtr');

        $this->setExpectedException('Exception');

        $sUT->isValid($config);
    }
}
