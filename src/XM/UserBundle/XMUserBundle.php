<?php

namespace XM\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class XMUserBundle extends Bundle
{
    public function getParent()
    {
        return "FOSUserBundle";
    }
}
