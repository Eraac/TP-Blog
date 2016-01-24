<?php

namespace LKE\BlogBundle\Controller;

use LKE\BlogBundle\Entity\Comment;
use LKE\BlogBundle\Form\Type\CommentType;
use LKE\BlogBundle\Security\PostVoter;
use LKE\CoreBundle\Controller\CoreController;
use LKE\CoreBundle\Security\Voter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

class PostController extends CoreController
{
    const MAX_ARTICLE_PER_PAGE = 10;

    public function listAction(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $page = $request->query->getInt('page', 1);

        $pagination = $paginator->paginate(
            $this->getRepository()->queryListPublishedPost(self::MAX_ARTICLE_PER_PAGE, $page),
            $page,
            self::MAX_ARTICLE_PER_PAGE
        );

        return $this->render("LKEBlogBundle:Post:list.html.twig", [
            'pagination' => $pagination
        ]);
    }

    public function viewAction(Request $request, $slug)
    {
        $post = $this->getEntity($slug, Voter::VIEW, ["method" => "findFullBySlug"]);

        $formComment = $this->generateCommentForm(new Comment(), $request);

        return $this->render('LKEBlogBundle:Post:view.html.twig', [
            'post' => $post,
            'formComment' => $formComment->createView(),
        ]);
    }

    public function addCommentAction(Request $request, $slug)
    {
        $comment = new Comment();

        $post = $this->getEntity($slug, PostVoter::COMMENT, ['method' => "findBySlug"]);
        $comment->setPost($post);

        $form = $this->generateCommentForm($comment, $request);

        return $this->processCommentForm($form, $comment, $request);
    }

    private function generateCommentForm(Comment $comment, Request $request)
    {
        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);

        return $form;
    }

    private function processCommentForm(Form $form, Comment $comment, Request $request)
    {
        $slug = $comment->getPost()->getSlug();

        if ($form->isValid())
        {
            $this->getEm()->persist($comment);
            $this->getEm()->flush();

            $param = ['slug' => $slug];

            $this->addSuccess("lkeblog.success.comment.add");

            return $this->redirectToRoute("lke_blog_post_view", $param);
        }

        return $this->viewAction($request, $slug);
    }

    protected function getRepositoryName()
    {
        return "LKEBlogBundle:Post";
    }
}
