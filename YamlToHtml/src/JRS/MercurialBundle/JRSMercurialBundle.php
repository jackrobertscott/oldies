<?php

namespace JRS\MercurialBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JRSMercurialBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
