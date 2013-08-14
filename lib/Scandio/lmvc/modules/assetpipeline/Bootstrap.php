<?php

namespace Scandio\lmvc\modules\assetpipeline;

use Scandio\lmvc\LVC;

class Bootstrap extends \Scandio\lmvc\Bootstrap
{
    public static function configure($assetRootDirectory)
    {
        assetpipes\CssPipe::register('css', ['min']);
        assetpipes\SassPipe::register('scss', ['min']);
        assetpipes\LessPipe::register('less', ['min']);
        assetpipes\JsPipe::register('js', ['min']);
        assetpipes\CoffeescriptPipe::register('coffee', ['min']);

        assetpipes\ImagePipe::register('img', [LVC::get()->request->w, LVC::get()->request->h]);

        controllers\AssetPipeline::registerFlexOptions([
            LVC::get()->request->w => LVC::get()->request->w,
            LVC::get()->request->h => LVC::get()->request->h
        ]);

        controllers\AssetPipeline::configure($assetRootDirectory);
    }

    /**
     * Initialize the module
     */
    public function initialize()
    {
        LVC::registerControllerNamespace(new controllers\AssetPipeline());
    }
}