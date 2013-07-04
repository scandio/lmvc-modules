<?php

namespace Scandio\lmvc\modules\assetpipeline\controllers;

use Scandio\lmvc\LVC;
use Scandio\lmvc\Controller;
use Scandio\lmvc\modules\assetpipeline\interfaces;
use Scandio\lmvc\modules\assetpipeline\assetpipes;
use Scandio\lmvc\modules\assetpipeline\util;


class AssetPipeline extends Controller implements interfaces\iAssetPipeline
{
    private static
        $_cssPipe,
        $_sassPipe,
        $_lessPipe,
        $_jsPipe,
        $_helper;

    protected static
        $config = [],
        $defaults = [
            'stage' => 'dev',
            'assetRootDirectory' => '',
            'cacheDirectory' => '_cache',
            'assetDirectories' => [
                'js'    => [
                    'main'  => 'javascripts'
                ],
                'less'  => [
                    'main'  =>  'styles'
                ],
                'sass'  => [
                    'main'  =>  'styles'
                ],
                'css'   => [
                    'main'  =>  'styles'
                ]
            ]
        ];

    public function __construct(/* No dependency injection yet */) {
        static::$_cssPipe = new assetpipes\CssPipe();
        static::$_sassPipe = new assetpipes\SassPipe();
        static::$_lessPipe = new assetpipes\LessPipe();
        static::$_jsPipe = new assetpipes\JsPipe();

        static::$_helper = new util\AssetPipelineHelper();
    }

    public static function configure($config = [])
    {
        static::$config = array_replace_recursive(static::$defaults, $config);

        static::initialize();
    }

    public static function initialize() {
        util\FileLocator::setStage(static::$config['stage']);

        static::$_cssPipe->setCacheDirectory(static::$config['cacheDirectory']);
        static::$_sassPipe->setCacheDirectory(static::$config['cacheDirectory']);
        static::$_jsPipe->setCacheDirectory(static::$config['cacheDirectory']);
        static::$_lessPipe->setCacheDirectory(static::$config['cacheDirectory']);

        static::$_cssPipe->setAssetDirectory(
            static::$_helper->path([static::$config['assetRootDirectory'], static::$config['assetDirectories']['css']['main']]),
            static::$_helper->prefix(static::$config['assetDirectories']['css']['fallbacks'], static::$config['assetRootDirectory'])
        );

        static::$_sassPipe->setAssetDirectory(
            static::$_helper->path([static::$config['assetRootDirectory'], static::$config['assetDirectories']['sass']['main']]),
            static::$_helper->prefix(static::$config['assetDirectories']['sass']['fallbacks'], static::$config['assetRootDirectory'])
        );

        static::$_jsPipe->setAssetDirectory(
            static::$_helper->path([static::$config['assetRootDirectory'], static::$config['assetDirectories']['js']['main']]),
            static::$_helper->prefix(static::$config['assetDirectories']['js']['fallbacks'], static::$config['assetRootDirectory'])
        );

        static::$_lessPipe->setAssetDirectory(
            static::$_helper->path([static::$config['assetRootDirectory'], static::$config['assetDirectories']['less']['main']]),
            static::$_helper->prefix(static::$config['assetDirectories']['less']['fallbacks'], static::$config['assetRootDirectory'])
        );
    }

    public static function index()
    {
        echo "< Please specify a pipe as action as in: css|js|sass|less >";
    }

    public static function js(/* func_get_args = (options…, filenames…) */)
    {
        $args = func_get_args();

        echo static::$_jsPipe->serve(static::$_helper->getFiles($args), static::$_helper->getOptions($args));
    }

    public static function css(/* func_get_args = (options…, filenames…) */)
    {
        $args = func_get_args();

        echo static::$_cssPipe->serve(static::$_helper->getFiles($args), static::$_helper->getOptions($args));
    }

    public static function less(/* func_get_args = (options…, filenames…) */)
    {
        $args = func_get_args();

        echo static::$_lessPipe->serve(static::$_helper->getFiles($args), static::$_helper->getOptions($args));
    }

    public static function sass(/* func_get_args = (options…, filenames…) */)
    {
        $args = func_get_args();

        echo static::$_sassPipe->serve(static::$_helper->getFiles($args), static::$_helper->getOptions($args));
    }
}