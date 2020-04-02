<?php

namespace PriceList;

use Common\Tool\Configurator;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return Configurator::get(__DIR__ . '/..');
    }
}
