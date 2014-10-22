<?php
namespace CrudGenerator\Tests\General\Utils\Comparator;

use CrudGenerator\Utils\Comparator;
use Doctrine\Instantiator\Instantiator;
use Doctrine\Common\Annotations\AnnotationReader;

class CompareClassAndArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testCompareee()
    {
        $sUT = new Comparator(new Instantiator(), new AnnotationReader());

        $array = array(
            'config' => array(
                'metadataDaoFactory' => 'CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory',
                'driver' => 'yta',
                'response' => 'fez',
                'uniqueName' => 'fezfez'
            ),
            'definition' => 'fez',
            'metaDataDAO' => 'fez',
            'metaDataDAOFactory' => 'CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory',
            'falseDependencies' => 'fez',
            'uniqueName' => 'fez'
        );

        $sUT->compareClassAndArray('CrudGenerator\MetaData\MetaDataSource', $array);
    }
}
