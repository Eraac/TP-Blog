<?php

namespace LKE\BlogBundle\Twig;

use LKE\BlogBundle\Entity\Category;
use LKE\BlogBundle\Entity\Post;

class LKEBlogExtension extends \Twig_Extension
{
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('countPost', [$this, 'countPost']),
            new \Twig_SimpleFilter('countComment', [$this, 'countComment']),
        ];
    }

    public function countPost(Category $category)
    {
        $repo = $this->doctrine->getRepository("LKEBlogBundle:Post");
        $count = $repo->countPostPerCategory($category);

        return $count;
    }

    public function countComment(Post $post)
    {
        $repo = $this->doctrine->getRepository("LKEBlogBundle:Comment");
        $count = $repo->countComment($post);

        return $count;
    }

    public function getName()
    {
        return "lke_blog_extension";
    }
}
