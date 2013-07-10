<?php
namespace CrudGenerator\Tests\MetaData\Doctrine2\Doctrine2MetaDataDAO;

use CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

class getMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $suT = Doctrine2MetaDataDAOFactory::getInstance();

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Doctrine2\MetadataDataObjectDoctrine2',
            $suT->getMetadataFor('Corp\News\NewsEntity')
        );
    }
}

