<?php

namespace Scandio\lmvc\modules\upload;

use Scandio\lmvc\LVC;
use Scandio\lmvc\modules\upload\controllers;

class Bootstrap extends \Scandio\lmvc\Bootstrap
{

    public static function configure($config = [])
    {
        controllers\Upload::configure($config);
    }

    public function initialize()
    {
        LVC::registerControllerNamespace(new controllers\Upload());
    }
}