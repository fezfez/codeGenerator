<?php

namespace TestZf2\Entities;

use TestZf2\Entities\CommentEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SuiviNews

 * @ORM\Table(name="suivi_news")
 * @ORM\Entity
 */
class NewsEntity
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
     * @var CommentEntity
     *
     * @ORM\ManyToOne(targetEntity="\TestZf2\Entities\CommentEntity")
     */
    private $comment;
    /**
     * @var CommentEntity
     *
     * @ORM\OneToOne(targetEntity="\TestZf2\Entities\CommentEntity")
     */
    private $commentInOneToOne;
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\TestZf2\Entities\CommentEntity", mappedBy="commentInOneToMany")
     */
    private $commentInOneToMany;
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\TestZf2\Entities\CommentEntity")
     */
    private $commentInManyToMany;

    public function __construct()
    {
        $this->commentInOneToMany  = new ArrayCollection();
        $this->commentInManyToMany = new ArrayCollection();
    }

    /**
     * Set comment
     *
     * @param CommentEntity $value
     * @return NewsEntity
     */
    public function setComment(CommentEntity $value)
    {
        $this->comment = $value;
        return $this;
    }
    /**
     * Set comment
     *
     * @return NewsEntity
     */
    public function setCommentInOneToOne(CommentEntity $value)
    {
        $this->comment = $value;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return \TestZf2\Entities\CommentEntity
     */
    public function getComment()
    {
        return $this->comment;
    }
    /**
     * @return \TestZf2\Entities\CommentEntity
     */
    public function getCommentInOneToOne()
    {
        return $this->commentInOneToOne;
    }
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCommentInOneToMany()
    {
        return $this->commentInOneToMany;
    }
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCommentInManyToMany()
    {
        return $this->commentInManyToMany;
    }
}
