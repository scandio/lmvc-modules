<?php

namespace Scandio\lmvc\modules\mustache;

use \Scandio\lmvc\LVC;

class Bootstrap extends \Scandio\lmvc\Bootstrap {
    public function initialize() {
        LVC::registerControllerNamespace(new controllers\MustacheRest);
    }
}
