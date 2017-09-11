<?php

namespace Hris\TemplateBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HrisTemplateBundle extends Bundle
{
	 public function getParent()
    {
        return 'GistTemplateBundle';
    }
}
