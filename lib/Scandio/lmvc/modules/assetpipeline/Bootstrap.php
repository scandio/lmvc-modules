<?php

namespace Scandio\lmvc\modules\assetpipeline;

use Scandio\lmvc\LVC;
use Scandio\lmvc\modules\assetpipeline\controllers;
use Scandio\lmvc\modules\assetpipeline;

class Bootstrap extends \Scandio\lmvc\Bootstrap
{

    public static function configure($config = []) {
        controllers\AssetPipeline::configure($config);
    }

    /**
     * Initialize the module
     */
    public function initialize()
    {
        LVC::registerControllerNamespace(new controllers\AssetPipeline());
    }
}