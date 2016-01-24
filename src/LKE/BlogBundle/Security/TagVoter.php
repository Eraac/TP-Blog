<?php

namespace LKE\BlogBundle\Security;

use LKE\CoreBundle\Security\Voter as BaseVoter;
use LKE\UserBundle\Entity\User;
use LKE\BlogBundle\Entity\Tag;

class TagVoter extends BaseVoter
{
    protected function supports($attribute, $subject)
    {
        return parent::supports($attribute, $subject) && $subject instanceof Tag;
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

    // canEdit & canDelete is not override because BaseVoter::voteOnAttribute return true for admin (and only admin can edit/delete tag)
}
