<?php

namespace LKE\BlogBundle\Model;

use LKE\BlogBundle\Entity\Post;

class ChoicePost
{
    /**
     * @var Post
     */
    private $post;

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
    }
}
