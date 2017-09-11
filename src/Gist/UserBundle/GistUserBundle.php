<?php

namespace Gist\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GistUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
