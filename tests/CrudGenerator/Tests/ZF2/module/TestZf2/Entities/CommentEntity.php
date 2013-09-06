<?php

namespace TestZf2\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuiviNews

 * @ORM\Table(name="comment")
 * @ORM\Entity
 */
class CommentEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_creat", type="string")
     */
    private $dtCreat;
    /**
     * @var string
     *
     * @ORM\Column(name="titre_new", type="string", length=50, nullable=false)
     */
    private $titreComment;
    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="\TestZf2\Entities\NewsEntity", inversedBy="commentInOneToMany")
     */
    private $commentInOneToMany;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dtCreat
     *
     * @param \DateTime $dtCreat
     * @return NewsEntity
     */
    public function setDtCreat($dtCreat)
    {
        $this->dtCreat = $dtCreat;

        return $this;
    }

    /**
     * Get dtCreat
     *
     * @return \DateTime
     */
    public function getDtCreat()
    {
        return $this->dtCreat;
    }

    /**
     * Set nomLog
     *
     * @param string $nomLog
     * @return NewsEntity
     */
    public function setNomLog($nomLog)
    {
        $this->nomLog = $nomLog;

        return $this;
    }

    /**
     * Get nomLog
     *
     * @return string
     */
    public function getNomLog()
    {
        return $this->nomLog;
    }

    /**
     * Set verLog
     *
     * @param string $verLog
     * @return NewsEntity
     */
    public function setVerLog($verLog)
    {
        $this->verLog = $verLog;

        return $this;
    }

    /**
     * Get verLog
     *
     * @return string
     */
    public function getVerLog()
    {
        return $this->verLog;
    }

    /**
     * Set titreNew
     *
     * @param string $titreNew
     * @return NewsEntity
     */
    public function setTitreNew($titreNew)
    {
        $this->titreNew = $titreNew;

        return $this;
    }

    /**
     * Get titreNew
     *
     * @return string
     */
    public function getTitreNew()
    {
        return $this->titreNew;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return NewsEntity
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set nivutil
     *
     * @param string $nivutil
     * @return NewsEntity
     */
    public function setNivutil($nivutil)
    {
        $this->nivutil = $nivutil;

        return $this;
    }

    /**
     * Get nivutil
     *
     * @return string
     */
    public function getNivutil()
    {
        return $this->nivutil;
    }

    /**
     * Get idNew
     *
     * @return integer
     */
    public function getIdNew()
    {
        return $this->idNew;
    }
}
