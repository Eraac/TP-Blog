<?php

namespace LKE\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('LKEAdminBundle:Admin:index.html.twig');
    }
}
