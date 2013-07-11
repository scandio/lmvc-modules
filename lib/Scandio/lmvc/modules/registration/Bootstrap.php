<?php

namespace Scandio\lmvc\modules\registration;

use Scandio\lmvc\LVC;

class Bootstrap extends \Scandio\lmvc\Bootstrap
{
    /**
     * Registers the module controller namespace and the views directory
     */
    public function initialize()
    {
        LVC::registerControllerNamespace(new controllers\Registration());
        LVC::registerViewDirectory(static::getPath() . '/views/');
    }
}