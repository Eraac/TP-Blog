<?php

namespace LKE\BlogBundle\Controller;

use LKE\CoreBundle\Controller\CoreController;
use LKE\CoreBundle\Security\Voter;

class TagController extends CoreController
{
    public function listAction()
    {
        $tags = $this->getRepository()->findBy([], ['name' => 'ASC']);

        return $this->render("LKEBlogBundle:Tag:list.html.twig", [
            "tags" => $tags,
        ]);
    }

    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($slug)
    {
        $tag = $this->getEntity($slug, Voter::VIEW, ['method' => 'findBySlug']);
        $posts = $this->getRepository("LKEBlogBundle:Post")->findPreviewByTag($tag);

        return $this->render("LKEBlogBundle:Tag:view.html.twig", [
            "tag" => $tag,
            "posts" => $posts,
        ]);
    }

    /**
     * @return string
     */
    protected function getRepositoryName()
    {
        return "LKEBlogBundle:Tag";
    }
}
