<?php

namespace LKE\AdminBundle\Controller;

use LKE\BlogBundle\Entity\Tag;
use LKE\BlogBundle\Form\Type\TagType;
use LKE\BlogBundle\Model\ChoiceTag;
use LKE\BlogBundle\Form\Type\ChoiceTagType;
use LKE\CoreBundle\Controller\CoreController;
use LKE\CoreBundle\Security\Voter;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class TagController extends CoreController
{
    public function indexAction(Request $request)
    {
        $choiceTag = new ChoiceTag();
        $form = $this->generateChoiceList($choiceTag, $request);

        return $this->processChoiceList($form, $choiceTag);
    }

    public function addAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->generateForm($tag, $request);

        if ($this->processForm($form, $tag))
        {
            $this->addSuccess('lkeadmin.success.tag.add');

            return $this->redirectToRoute('lke_admin_index');
        }
        else {
            return $this->render('LKEAdminBundle:Tag:add.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    public function editAction(Request $request, $id)
    {
        $tag = $this->getEntity($id, Voter::EDIT);
        $form = $this->generateForm($tag, $request);

        if ($this->processForm($form, $tag))
        {
            $this->addSuccess('lkeadmin.success.tag.edit');

            return $this->redirectToRoute('lke_admin_index');
        }
        else {
            return $this->render('LKEAdminBundle:Tag:edit.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    public function removeAction($id)
    {
        $tag = $this->getEntity($id, Voter::DELETE);

        $em = $this->getEm();
        $em->remove($tag);
        $em->flush();

        $this->addSuccess('lkeadmin.success.tag.remove');

        return $this->redirectToRoute('lke_admin_index');
    }

    private function generateForm(Tag $tag, Request $request)
    {
        $form = $this->createForm(new TagType(), $tag);
        $form->handleRequest($request);

        return $form;
    }

    private function processForm(Form $form, Tag $tag)
    {
        if ($form->isValid())
        {
            $em = $this->getEm();
            $em->persist($tag);
            $em->flush();

            return true;
        }

        return false;
    }

    private function generateChoiceList(ChoiceTag $choiceTag, Request $request)
    {
        $form = $this->createForm(new ChoiceTagType(), $choiceTag);
        $form->handleRequest($request);

        return $form;
    }

    private function processChoiceList(Form $form, ChoiceTag $choiceTag)
    {
        if ($form->isValid())
        {
            $param = ['id' => $choiceTag->getTag()->getId()];
            $route = ($form->get('edit')->isClicked()) ? 'lke_admin_tag_edit' : 'lke_admin_tag_remove';

            return $this->redirectToRoute($route, $param);
        }

        return $this->render('LKEAdminBundle:Tag:index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    protected function getRepositoryName()
    {
        return "LKEBlogBundle:Tag";
    }
}
