<?php

namespace LKE\BlogBundle\Model;

use LKE\BlogBundle\Entity\Tag;

class ChoiceTag
{
    /**
     * @var Tag
     */
    private $tag;

    /**
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param Tag $tag
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;
    }
}
