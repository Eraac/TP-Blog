<?php

namespace LKE\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LKEUserBundle extends Bundle
{
    public function getParent()
    {
        return "FOSUserBundle";
    }
}
