<?php

namespace LKE\BlogBundle\Security;

use LKE\CoreBundle\Security\Voter as BaseVoter;
use LKE\BlogBundle\Entity\Category;

class CategoryVoter extends BaseVoter
{
    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return parent::supports($attribute, $subject) && $subject instanceof Category;
    }

    /**
     * @param Category $category
     * @param \LKE\UserBundle\Entity\User $user
     * @return bool
     */
    protected function canView($category, $user)
    {
        return true;
    }

    // canEdit & canDelete is not override because BaseVoter::voteOnAttribute return true for admin (and only admin can edit/delete category)
}
