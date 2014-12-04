<?php

namespace Esokia\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EsokiaUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
