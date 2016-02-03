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

    /**
     * @param AccessDecisionManager $decisionManager
     */
    public function __construct(AccessDecisionManager $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE]);
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->isAdmin($token)) {
            return true;
        }

        $user = $token->getUser();
        $method = 'can' . ucfirst($attribute);

        if (method_exists($this, $method)) {
            return $this->$method($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param TokenInterface $token
     * @return bool
     */
    private function isAdmin(TokenInterface $token)
    {
        return $this->decisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param object $entity
     * @param \LKE\UserBundle\Entity\User $user
     * @return bool
     */
    protected function canView($entity, $user)
    {
        return false;
    }

    /**
     * @param $entity
     * @param \LKE\UserBundle\Entity\User $user
     * @return bool
     */
    protected function canEdit($entity, $user)
    {
        return false;
    }

    /**
     * @param $entity
     * @param \LKE\UserBundle\Entity\User $user
     * @return bool
     */
    protected function canDelete($entity, $user)
    {
        return false;
    }
}
