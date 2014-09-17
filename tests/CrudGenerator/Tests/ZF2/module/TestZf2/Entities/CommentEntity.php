<?php

namespace TestZf2\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use TestZf2\Entities\NewsEntity;

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
    private $dateCreate;
    /**
     * @var string
     *
     * @ORM\Column(name="titre_new", type="string", length=50, nullable=false)
     */
    private $title;
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="\TestZf2\Entities\NewsEntity", inversedBy="commentInOneToMany")
     */
    private $commentInOneToMany;

    public function __construct()
    {
        $this->commentInOneToMany = new ArrayCollection();
    }

    /**
     * Set DateCreate
     *
     * @param \DateTime $value
     * @return CommentEntity
     */
    public function setDateCreate(\DateTime $value)
    {
        $this->dateCreate = $value;
        return $this;
    }
    /**
     * Set title
     *
     * @param string $value
     * @return CommentEntity
     */
    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }
    /**
     * Set CommentInOneToMany
     *
     * @param NewsEntity $value
     * @return CommentEntity
     */
    public function setCommentInOneToMany(NewsEntity $value)
    {
        $this->commentInOneToMany->add($value);
        return $this;
    }

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
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCommentInOneToMany()
    {
        return $this->commentInOneToMany;
    }
}
