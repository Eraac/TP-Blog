<?php

namespace LKE\AdminBundle\Controller;

use LKE\BlogBundle\Model\ChoicePost;
use LKE\BlogBundle\Form\Type\ChoicePostType;
use LKE\BlogBundle\Entity\Post;
use LKE\BlogBundle\Form\Type\PostType;
use LKE\CoreBundle\Controller\CoreController;
use LKE\CoreBundle\Security\Voter;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class PostController extends CoreController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $choicePost = new ChoicePost();
        $form = $this->generateChoiceList($choicePost, $request);

        return $this->processChoiceList($form, $choicePost);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $post = new Post();
        $post->setAuthor($this->getUser());
        $form = $this->generateForm($post, $request);

        if ($this->processForm($form, $post))
        {
            $this->addSuccess('lkeadmin.success.post.add');

            return $this->redirectToRoute('lke_admin_index');
        }
        else {
            return $this->render('LKEAdminBundle:Post:add.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $post = $this->getEntity($id, Voter::EDIT);
        $form = $this->generateForm($post, $request);

        if ($this->processForm($form, $post))
        {
            $this->addSuccess('lkeadmin.success.post.edit');

            return $this->redirectToRoute('lke_admin_index');
        }
        else {
            return $this->render('LKEAdminBundle:Post:edit.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($id)
    {
        $post = $this->getEntity($id, Voter::DELETE);

        $em = $this->getEm();
        $em->remove($post);
        $em->flush();

        $this->addSuccess('lkeadmin.success.post.remove');

        return $this->redirectToRoute('lke_admin_index');
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return Form
     */
    private function generateForm(Post $post, Request $request)
    {
        $form = $this->createForm(new PostType(), $post);
        $form->handleRequest($request);

        return $form;
    }

    /**
     * @param Form $form
     * @param Post $post
     * @return bool
     */
    private function processForm(Form $form, Post $post)
    {
        if ($form->isValid())
        {
            $em = $this->getEm();
            $em->persist($post);
            $em->flush();

            return true;
        }

        return false;
    }

    /**
     * @param ChoicePost $choicePost
     * @param Request $request
     * @return Form
     */
    private function generateChoiceList(ChoicePost $choicePost, Request $request)
    {
        $form = $this->createForm(new ChoicePostType(), $choicePost);
        $form->handleRequest($request);

        return $form;
    }

    /**
     * @param Form $form
     * @param ChoicePost $choicePost
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function processChoiceList(Form $form, ChoicePost $choicePost)
    {
        if ($form->isValid())
        {
            $param = ['id' => $choicePost->getPost()->getId()];
            $route = ($form->get('edit')->isClicked()) ? 'lke_admin_post_edit' : 'lke_admin_post_remove';

            return $this->redirectToRoute($route, $param);
        }

        return $this->render('LKEAdminBundle:Post:index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @return string
     */
    protected function getRepositoryName()
    {
        return "LKEBlogBundle:Post";
    }
}
