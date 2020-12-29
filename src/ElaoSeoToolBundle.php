<?php

declare(strict_types=1);

namespace Elao\Bundle\SeoTool;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElaoSeoToolBundle extends Bundle
{
    public function getPath()
    {
        return \dirname(__DIR__);
    }
}