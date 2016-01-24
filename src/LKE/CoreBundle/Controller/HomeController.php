<?php

namespace LKE\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    const MAX_POSTS_HOMEPAGE = 5;

    public function indexAction()
    {
        $posts = $this->getDoctrine()->getRepository("LKEBlogBundle:Post")->findLastPublishedPost(self::MAX_POSTS_HOMEPAGE);

        return $this->render("LKECoreBundle:Home:index.html.twig", [
            'posts' => $posts,
        ]);
    }
}
