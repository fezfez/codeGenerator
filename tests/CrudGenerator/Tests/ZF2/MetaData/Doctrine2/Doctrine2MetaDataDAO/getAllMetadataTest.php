<?php
namespace CrudGenerator\Tests\ZF2\MetaData\Doctrine2\Doctrine2MetaDataDAO;

use CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

class getAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $suT = Doctrine2MetaDataDAOFactory::getInstance();

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection',
            $suT->getAllMetadata()
        );
    }
}

