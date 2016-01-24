<?php

namespace LKE\BlogBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use LKE\CoreBundle\Entity\Image;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="LKE\BlogBundle\Repository\PostRepository")
 */
class Post
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishedAt", type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @var LKE\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="LKE\UserBundle\Entity\User")
     */
    private $author;

    /**
     * @var LKE\BlogBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="LKE\BlogBundle\Entity\Category")
     * @Assert\NotNull()
     * @Assert\Valid
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="LKE\BlogBundle\Entity\Tag")
     * @Assert\Valid
     */
    private $tags;

    /**
     * @ORM\OneToOne(targetEntity="LKE\CoreBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Assert\Valid
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="LKE\BlogBundle\Entity\Comment", mappedBy="post")
     */
    private $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Post
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set category
     *
     * @param \LKE\BlogBundle\Entity\Category $category
     *
     * @return Post
     */
    public function setCategory(\LKE\BlogBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \LKE\BlogBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add tag
     *
     * @param \LKE\BlogBundle\Entity\Tag $tag
     *
     * @return Post
     */
    public function addTag(\LKE\BlogBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \LKE\BlogBundle\Entity\Tag $tag
     */
    public function removeTag(\LKE\BlogBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**!
     * @return Entity\User|LKE\UserBundle
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Entity\User|LKE\UserBundle $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage(Image $image)
    {
        $this->image = $image;
    }

    public function isPublished()
    {
        $now = new \DateTime();

        return $this->publishedAt <= $now;
    }

    /**
     * Add comment
     *
     * @param \LKE\BlogBundle\Entity\Comment $comment
     *
     * @return Post
     */
    public function addComment(\LKE\BlogBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \LKE\BlogBundle\Entity\Comment $comment
     */
    public function removeComment(\LKE\BlogBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
