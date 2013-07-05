<?php

namespace Scandio\lmvc\modules\assetpipeline;

use Scandio\lmvc\LVC;
use Scandio\lmvc\modules\assetpipeline\controllers;
use Scandio\lmvc\modules\assetpipeline;
use Scandio\lmvc\modules\assetpipeline\assetpipes;

class Bootstrap extends \Scandio\lmvc\Bootstrap
{

    public static function configure($config = [])
    {
        assetpipes\CssPipe::register();
        assetpipes\SassPipe::register();
        assetpipes\LessPipe::register();
        assetpipes\JsPipe::register();

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