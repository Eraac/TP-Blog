<?php

namespace LKE\BlogBundle\Controller;

use LKE\CoreBundle\Controller\CoreController;
use LKE\CoreBundle\Security\Voter;

class CategoryController extends CoreController
{
    public function listAction()
    {
        $categories = $this->getRepository()->findBy([], ['name' => 'ASC']);

        return $this->render("LKEBlogBundle:Category:list.html.twig", [
            "categories" => $categories,
        ]);
    }

    public function viewAction($slug)
    {
        $category = $this->getEntity($slug, Voter::VIEW, ['method' => 'findBySlug']);
        $posts = $this->getRepository("LKEBlogBundle:Post")->findPreviewByCategory($category);

        return $this->render("LKEBlogBundle:Category:view.html.twig", [
            "category" => $category,
            "posts" => $posts,
        ]);
    }

    protected function getRepositoryName()
    {
        return "LKEBlogBundle:Category";
    }
}
