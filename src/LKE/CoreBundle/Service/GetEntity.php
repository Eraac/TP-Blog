<?php

namespace LKE\CoreBundle\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class GetEntity
{
    private $doctrine;
    private $security;

    public function __construct($doctrine, AuthorizationChecker $security)
    {
        $this->doctrine = $doctrine;
        $this->security = $security;
    }

    public function get($id, $access, array $options)
    {
        $repo = $this->getRepository($options['repository']);

        $method = $options['method'];

        if (!method_exists($repo, $method)) {
            throw new \LogicException("Method : '" . $method . "' in repository : '" . $options['repository'] . "' does not exist");
        }

        $entity = $repo->$method($id);

        if (is_null($entity)) {
            throw new NotFoundHttpException('Sorry ' . $options['repository'] . ' : \'' . $id . '\' does not exist');
        }

        if (!$this->security->isGranted($access, $entity)) {
            throw new AccessDeniedException();
        }

        return $entity;
    }

    private function getRepository($name)
    {
        return $this->doctrine->getRepository($name);
    }
}
