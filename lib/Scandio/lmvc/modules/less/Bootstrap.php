<?php

namespace Scandio\lmvc\modules\less;

use \Scandio\lmvc\LVC;

class Bootstrap extends \Scandio\lmvc\Bootstrap {
    public function initialize() {
        LVC::registerControllerNamespace(new controllers\Less);
    }
}
