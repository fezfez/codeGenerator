<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigDAO;

use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\Utils\TranstyperFactory;
use KeepUpdate\ArrayValidatorFactory;
use CrudGenerator\MetaData\Driver\DriverHydrator;
use CrudGenerator\Tests\TestCase;

class RetrieveAllTest extends TestCase
{
    public function testWithOneWrongConfigured()
    {
        $rawMocks = $this->createSut('CrudGenerator\MetaData\Config\MetaDataConfigDAO');

        $fileManagerExpectsGlob = $rawMocks['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsGlob->method('glob');
        $fileManagerExpectsGlob->will($this->returnValue(array('myFile')));

        $rawData = array('my data');

        $fileManagerExpectsFileGetContents = $rawMocks['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsFileGetContents->method('fileGetContent');
        $fileManagerExpectsFileGetContents->will($this->returnValue($rawData));

        $transtyperExpectsDecode = $rawMocks['mocks']['transtyper']->expects($this->once());
        $transtyperExpectsDecode->method('decode');
        $transtyperExpectsDecode->with($rawData);
        $transtyperExpectsDecode->will($this->returnValue($rawData));

        $arrayValidatorExpectsIsValid = $rawMocks['mocks']['arrayValidator']->expects($this->once());
        $arrayValidatorExpectsIsValid->method('isValid');
        $arrayValidatorExpectsIsValid->with($this->anything(), $rawData);
        $arrayValidatorExpectsIsValid->will($this->throwException(new \KeepUpdate\ValidationException()));

        /* @var $sUT \CrudGenerator\MetaData\Config\MetaDataConfigDAO */
        $sUT     = $rawMocks['instance']($rawMocks['mocks']);
        $results = $sUT->retrieveAll();

        $this->assertCount(0, $results);
        $this->assertInstanceOf('CrudGenerator\MetaData\MetaDataSourceCollection', $results);
    }

    public function testWithoutConfig()
    {
        $rawData = array(
            MetaDataSource::METADATA_DAO_FACTORY => 'CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory'
        );

        $rawMocks = $this->createSut('CrudGenerator\MetaData\Config\MetaDataConfigDAO');

        $fileManagerExpectsGlob = $rawMocks['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsGlob->method('glob');
        $fileManagerExpectsGlob->will($this->returnValue(array('myFile')));

        $fileManagerExpectsFileGetContents = $rawMocks['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsFileGetContents->method('fileGetContent');
        $fileManagerExpectsFileGetContents->will($this->returnValue($rawData));

        $transtyperExpectsDecode = $rawMocks['mocks']['transtyper']->expects($this->once());
        $transtyperExpectsDecode->method('decode');
        $transtyperExpectsDecode->with($rawData);
        $transtyperExpectsDecode->will($this->returnValue($rawData));

        $arrayValidatorExpectsIsValid = $rawMocks['mocks']['arrayValidator']->expects($this->once());
        $arrayValidatorExpectsIsValid->method('isValid');
        $arrayValidatorExpectsIsValid->with($this->anything(), $rawData);

        $metadataSourceHydratorAdapterTo = $rawMocks['mocks']['metaDataSourceHydrator']->expects($this->once());
        $metadataSourceHydratorAdapterTo->method('adapterNameToMetaDataSource');
        $metadataSourceHydratorAdapterTo->with($rawData[MetaDataSource::METADATA_DAO_FACTORY]);
        $metadataSourceHydratorAdapterTo->will($this->returnValue(new MetaDataSource()));

        /* @var $sUT \CrudGenerator\MetaData\Config\MetaDataConfigDAO */
        $sUT     = $rawMocks['instance']($rawMocks['mocks']);
        $results = $sUT->retrieveAll();

        $this->assertCount(1, $results);
        $this->assertInstanceOf('CrudGenerator\MetaData\MetaDataSourceCollection', $results);
    }

    public function testWithConfig()
    {
        $rawData = array(
            MetaDataSource::CONFIG => array(

            ),
            MetaDataSource::METADATA_DAO_FACTORY => 'CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory'
        );

        $rawMocks = $this->createSut('CrudGenerator\MetaData\Config\MetaDataConfigDAO');

        $fileManagerExpectsGlob = $rawMocks['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsGlob->method('glob');
        $fileManagerExpectsGlob->will($this->returnValue(array('myFile')));

        $fileManagerExpectsFileGetContents = $rawMocks['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsFileGetContents->method('fileGetContent');
        $fileManagerExpectsFileGetContents->will($this->returnValue($rawData));

        $transtyperExpectsDecode = $rawMocks['mocks']['transtyper']->expects($this->once());
        $transtyperExpectsDecode->method('decode');
        $transtyperExpectsDecode->with($rawData);
        $transtyperExpectsDecode->will($this->returnValue($rawData));

        $arrayValidatorExpectsIsValid = $rawMocks['mocks']['arrayValidator']->expects($this->once());
        $arrayValidatorExpectsIsValid->method('isValid');
        $arrayValidatorExpectsIsValid->with($this->anything(), $rawData);

        $metadataSourceHydratorAdapterTo = $rawMocks['mocks']['metaDataSourceHydrator']->expects($this->once());
        $metadataSourceHydratorAdapterTo->method('adapterNameToMetaDataSource');
        $metadataSourceHydratorAdapterTo->with($rawData[MetaDataSource::METADATA_DAO_FACTORY]);
        $metadataSourceHydratorAdapterTo->will($this->returnValue(new MetaDataSource()));

        $driverHydratorArrayToDto = $rawMocks['mocks']['driverHydrator']->expects($this->once());
        $driverHydratorArrayToDto->method('arrayToDto');
        $driverHydratorArrayToDto->with($rawData[MetaDataSource::CONFIG]);
        $driverHydratorArrayToDto->will($this->returnValue(new DriverConfig('unique')));

        /* @var $sUT \CrudGenerator\MetaData\Config\MetaDataConfigDAO */
        $sUT     = $rawMocks['instance']($rawMocks['mocks']);
        $results = $sUT->retrieveAll();

        $this->assertCount(1, $results);
        $this->assertInstanceOf('CrudGenerator\MetaData\MetaDataSourceCollection', $results);
    }

}
