<?php

namespace LKE\UserBundle\Controller;

use LKE\CoreBundle\Controller\CoreController;
use LKE\CoreBundle\Security\Voter;
use Symfony\Component\HttpFoundation\Request;


class UserController extends CoreController
{
    const MAX_USER_PER_PAGE = 100;
    const MAX_POST_PER_PAGE = 10;

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $page = $request->query->getInt('page', 1);

        $pagination = $paginator->paginate(
            $this->getRepository()->queryListUser(self::MAX_USER_PER_PAGE, $page),
            $page,
            self::MAX_USER_PER_PAGE
        );

        return $this->render("LKEUserBundle:User:list.html.twig", [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, $id)
    {
        $user = $this->getEntity($id, Voter::VIEW);

        $paginator  = $this->get('knp_paginator');
        $page = $request->query->getInt('page', 1);
        $repo = $this->getDoctrine()->getRepository("LKEBlogBundle:Post");

        $pagination = $paginator->paginate(
            $repo->queryListPostPreviewByAuthor($user, self::MAX_POST_PER_PAGE, $page),
            $page,
            self::MAX_POST_PER_PAGE
        );

        return $this->render("LKEUserBundle:User:see.html.twig", [
            'pagination' => $pagination,
            'user' => $user,
        ]);
    }

    /**
     * @return string
     */
    protected function getRepositoryName()
    {
        return "LKEUserBundle:User";
    }
}
