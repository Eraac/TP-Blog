<?php

namespace LKE\BlogBundle\Security;

use LKE\CoreBundle\Security\Voter as BaseVoter;
use LKE\BlogBundle\Entity\Post;

class PostVoter extends BaseVoter
{
    const COMMENT = 'comment';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return (parent::supports($attribute, $subject) || $attribute === self::COMMENT) && $subject instanceof Post;
    }

    /**
     * @param Post $post
     * @param \LKE\UserBundle\Entity\User $user
     * @return bool
     */
    protected function canView($post, $user)
    {
        return $post->isPublished();
    }

    /**
     * @param Post $post
     * @param \LKE\UserBundle\Entity\User $user
     * @return bool
     */
    protected function canComment($post, $user)
    {
        return $this->canView($post, $user);
    }

    // canEdit & canDelete is not override because BaseVoter::voteOnAttribute return true for admin (and only admin can edit/delete post)
}
