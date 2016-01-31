<?php

namespace LKE\UserBundle\Security;

use LKE\CoreBundle\Security\Voter as BaseVoter;
use LKE\UserBundle\Entity\User;

class UserVoter extends BaseVoter
{
    protected function supports($attribute, $subject)
    {
        return parent::supports($attribute, $subject) && $subject instanceof User;
    }

    /**
     * @param Tag $tag
     * @param User $user
     * @return bool
     */
    protected function canView($tag, $user)
    {
        return true;
    }

    // canEdit & canDelete is not override because is handle by FOSUserBundle
}
