<?php

namespace Scandio\lmvc\modules\less;

use \Scandio\lmvc\LVC;

class Bootstrap extends \Scandio\lmvc\Bootstrap
{
    /**
     * Initialize the module
     */
    public function initialize()
    {
        LVC::registerControllerNamespace(new controllers\Less);
    }
}
