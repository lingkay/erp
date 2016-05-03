<?php

namespace Catalyst\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CatalystUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
