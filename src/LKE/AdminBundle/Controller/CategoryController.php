<?php

namespace LKE\AdminBundle\Controller;

use LKE\BlogBundle\Entity\Category;
use LKE\BlogBundle\Form\Type\CategoryType;
use LKE\BlogBundle\Form\Type\ChoiceCategoryType;
use LKE\BlogBundle\Model\ChoiceCategory;
use LKE\CoreBundle\Controller\CoreController;
use LKE\CoreBundle\Security\Voter;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends CoreController
{
    public function indexAction(Request $request)
    {
        $choiceCategory = new ChoiceCategory();
        $form = $this->generateChoiceList($choiceCategory, $request);

        return $this->processChoiceList($form, $choiceCategory);
    }

    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->generateForm($category, $request);

        if ($this->processForm($form, $category))
        {
            $this->addSuccess('lkeadmin.success.category.add');

            return $this->redirectToRoute('lke_admin_index');
        }
        else {
            return $this->render('LKEAdminBundle:Category:add.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    public function editAction(Request $request, $id)
    {
        $category = $this->getEntity($id, Voter::EDIT);

        $form = $this->generateForm($category, $request);

        if ($this->processForm($form, $category)) {
            $this->addSuccess('lkeadmin.success.category.edit');

            return $this->redirectToRoute('lke_admin_index');
        }
        else {
            return $this->render('LKEAdminBundle:Category:edit.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    public function removeAction($id)
    {
        $category = $this->getEntity($id, Voter::DELETE);

        $em = $this->getEm();
        $em->remove($category);
        $em->flush();

        $this->addSuccess('lkeadmin.success.category.remove');

        return $this->redirectToRoute('lke_admin_index');
    }

    private function generateForm(Category $category, Request $request)
    {
        $form = $this->createForm(new CategoryType(), $category);
        $form->handleRequest($request);

        return $form;
    }

    private function processForm(Form $form, Category $category)
    {
        if ($form->isValid())
        {
            $em = $this->getEm();
            $em->persist($category);
            $em->flush();

            return true;
        }

        return false;
    }

    private function generateChoiceList(ChoiceCategory $choiceCategory, Request $request)
    {
        $form = $this->createForm(new ChoiceCategoryType(), $choiceCategory);
        $form->handleRequest($request);

        return $form;
    }

    private function processChoiceList(Form $form, ChoiceCategory $choiceCategory)
    {
        if ($form->isValid())
        {
            $param = ['id' => $choiceCategory->getCategory()->getId()];
            $route = ($form->get('edit')->isClicked()) ? 'lke_admin_category_edit' : 'lke_admin_category_remove';

            return $this->redirectToRoute($route, $param);
        }

        return $this->render('LKEAdminBundle:Category:index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    protected function getRepositoryName()
    {
        return "LKEBlogBundle:Category";
    }
}
