<?php

namespace LKE\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LKE\CoreBundle\Security\Voter;

abstract class CoreController extends Controller
{
    /**
     * @param string $name
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($name = null)
    {
        if (is_null($name)) {
            $name = $this->getRepositoryName();
        }

        return $this->getDoctrine()->getRepository($name);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param string $message
     * @return string
     */
    protected function t($message)
    {
        return $this->get('translator')->trans($message);
    }

    /**
     * @param string $message
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function addSuccess($message)
    {
        $this->addFlash('success', $this->t($message));
    }

    /**
     * @param $id
     * @param $access
     * @param array $options
     * @return mixed
     */
    protected function getEntity($id, $access = Voter::VIEW, array $options = [])
    {
        $options = $this->getOptions($options);

        return $this->get('lke_core.get_entity')->get($id, $access, $options);
    }

    /**
     * @param array $options
     * @return array
     */
    private function getOptions(array $options = [])
    {
        $defaultOptions = [
            "repository" => $this->getRepositoryName(),
            "method" => "find",
        ];

        return array_merge($defaultOptions, $options);
    }

    /**
     * @return string
     */
    abstract protected function getRepositoryName();
}
