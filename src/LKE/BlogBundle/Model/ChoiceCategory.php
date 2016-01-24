<?php

namespace LKE\BlogBundle\Model;

use LKE\BlogBundle\Entity\Category;

class ChoiceCategory
{
    /**
     * @var Category
     */
    private $category;

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }
}
