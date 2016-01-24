<?php

namespace LKE\CoreBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\Voter\Voter as BaseVoter;

class Voter extends BaseVoter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected $decisionManager;

    public function __construct(AccessDecisionManager $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->isAdmin($token)) {
            return true;
        }

        $user = $token->getUser();

        if (method_exists($this, $attribute)) {
            return $this->$attribute($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function isAdmin(TokenInterface $token)
    {
        return $this->decisionManager->decide($token, ['ROLE_ADMIN']);
    }

    protected function canView($entity, $user)
    {
        return false;
    }

    protected function canEdit($entity, $user)
    {
        return false;
    }

    protected function canDelete($entity, $user)
    {
        return false;
    }
}
