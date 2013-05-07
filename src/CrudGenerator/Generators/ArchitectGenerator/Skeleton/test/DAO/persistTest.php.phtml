<?php

namespace ApplicationTest\Model\Corp\Client\ClientDAO;

use DateTime;

use ApplicationTest\FixtureManager;

use Corp\Client\ClientDAO;
use Corp\Client\ClientDataObject;

class persistTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectResult()
    {
        FixtureManager::purge();
        $em = FixtureManager::getEm();

        $numAdeli = 'my_numadeli';
        $logiciel = 'logiciel';
        $niveau = 'niveau';
        $clef = 'clef';
        $nivUtil = 'nivUtil';
        $nom = 'nom';
        $prenom = 'prenom';
        $typeContract = 'typeContract';
        $version = 'version';
        $dateFin = new DateTime();

        $dataObject = new ClientDataObject();
        $dataObject->setClef($clef)
                   ->setLogiciel($logiciel)
                   ->setNiveau($niveau)
                   ->setNivUtil($nivUtil)
                   ->setNoAdeli($numAdeli)
                   ->setNom($nom)
                   ->setPrenom($prenom)
                   ->setTypeContract($typeContract)
                   ->setVersion($version)
                   ->setDateFin($dateFin);

        $clientDAO = new ClientDAO($em);
        $clientDAO->persist($dataObject);

        $clefXcabEntity = $em->getRepository('\Corp\Client\ClefXcabEntity')->findOneBy(array('noAdeli' => $numAdeli));
        $clientXcabEntity = $em->getRepository('\Corp\Client\ClientXcabEntity')->findOneBy(array('noAdeli' => $numAdeli));
        $contratXcab = $em->getRepository('\Corp\Doctrine\Entity\ContratXcab')->findOneBy(array('noAdeli' => $numAdeli));

        $this->assertEquals($clef, $clefXcabEntity->getClef());
        $this->assertEquals($numAdeli, $clefXcabEntity->getNoAdeli());
        $this->assertEquals($version, $clefXcabEntity->getVersion());

        $this->assertEquals($logiciel, $clientXcabEntity->getLogiciel());
        $this->assertEquals($niveau, $clientXcabEntity->getNiveau());
        $this->assertEquals($nivUtil, $clientXcabEntity->getNivUtil());
        $this->assertEquals($numAdeli, $clientXcabEntity->getNoAdeli());
        $this->assertEquals($nom, $clientXcabEntity->getNom());
        $this->assertEquals($prenom, $clientXcabEntity->getPrenom());

        $this->assertEquals($dateFin, $contratXcab->getDateFin());
        $this->assertEquals($numAdeli, $contratXcab->getNoAdeli());
        $this->assertEquals($typeContract, $contratXcab->getTypeContrat());
    }
}