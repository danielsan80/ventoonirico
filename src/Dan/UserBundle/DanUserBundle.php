<?php

namespace Dan\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DanUserBundle extends Bundle
{
    
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
