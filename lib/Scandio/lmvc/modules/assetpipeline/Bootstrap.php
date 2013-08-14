<?php

namespace Scandio\lmvc\modules\assetpipeline;

use Scandio\lmvc\LVC;
use Scandio\lmvc\modules\assetpipeline\controllers;
use Scandio\lmvc\modules\assetpipeline;
use Scandio\lmvc\modules\assetpipeline\assetpipes;

class Bootstrap extends \Scandio\lmvc\Bootstrap
{

    private function _configure()
    {
        assetpipes\CssPipe::register(['css'], ['min']);
        assetpipes\SassPipe::register(['sass', 'scss'], ['min']);
        assetpipes\LessPipe::register(['less'], ['min']);
        assetpipes\JsPipe::register(['js'], ['min']);
        assetpipes\CoffeescriptPipe::register(['coffee'], ['min']);
        assetpipes\ImagePipe::register(['img'], [LVC::get()->request->w, LVC::get()->request->h]);

        controllers\AssetPipeline::registerFlexOptions([
            LVC::get()->request->w => LVC::get()->request->w,
            LVC::get()->request->h => LVC::get()->request->h
        ]);

        controllers\AssetPipeline::configure();

    }

    public static function setRootDirectory($setRootDirectory)
    {
        controllers\AssetPipeline::setRootDirectory($setRootDirectory);
    }

    /**
     * Initialize the module
     */
    public function initialize()
    {
        LVC::registerControllerNamespace(new controllers\AssetPipeline());

        $this->_configure();
    }
}