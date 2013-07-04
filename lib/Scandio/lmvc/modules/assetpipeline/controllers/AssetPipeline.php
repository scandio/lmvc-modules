<?php

namespace Scandio\lmvc\modules\assetpipeline\controllers;

use Scandio\lmvc\LVC;
use Scandio\lmvc\Controller;
use Scandio\lmvc\modules\assetpipeline\interfaces;
use Scandio\lmvc\modules\assetpipeline\assetpipes;


class AssetPipeline extends Controller implements interfaces\iAssetPipeline
{
    private static
        $_cssPipe,
        $_sassPipe,
        $_lessPipe,
        $_jsPipe;

    protected static
        $config = [],
        $defaults = [
            'assetRootDirectory' => '',
            'cacheDirectory' => '_cache',
            'assetDirectories' => [
                'js'    => 'javascripts',
                'less'  => 'styles',
                'sass'  => 'styles',
                'css'   => 'styles'
            ]
        ];

    public function __construct(/* No dependency injection yet */) {
        static::$_cssPipe = new assetpipes\CssPipe();
        static::$_sassPipe = new assetpipes\SassPipe();
        static::$_lessPipe = new assetpipes\LessPipe();
        static::$_jsPipe = new assetpipes\JsPipe();
    }

    public static function configure($config = [])
    {
        static::$config = array_merge(static::$defaults, $config);
        static::initialize();
    }

    public static function initialize() {
        static::$_cssPipe->setCacheDirectory(static::$config['cacheDirectory']);
        static::$_sassPipe->setCacheDirectory(static::$config['cacheDirectory']);
        static::$_jsPipe->setCacheDirectory(static::$config['cacheDirectory']);
        static::$_lessPipe->setCacheDirectory(static::$config['cacheDirectory']);

        static::$_cssPipe->setAssetDirectory(
            static::$config['assetRootDirectory'] . DIRECTORY_SEPARATOR .
            static::$config['assetDirectories']['css']
        );

        static::$_sassPipe->setAssetDirectory(
            static::$config['assetRootDirectory']. DIRECTORY_SEPARATOR .
            static::$config['assetDirectories']['sass']
        );

        static::$_jsPipe->setAssetDirectory(
            static::$config['assetRootDirectory'] . DIRECTORY_SEPARATOR .
            static::$config['assetDirectories']['js']
        );

        static::$_lessPipe->setAssetDirectory(
            static::$config['assetRootDirectory'] . DIRECTORY_SEPARATOR .
            static::$config['assetDirectories']['less']
        );
    }

    public static function index()
    {
        echo "< Please specify a pipe as action as in: css|js|sass|less >";
    }

    public static function js(/* func_get_args = (options…, filename) */)
    {
        $args = func_get_args();

        echo static::$_jsPipe->serve(end($args), array_slice($args, 0, -1, true));
    }

    public static function css(/* func_get_args = (options…, filename) */)
    {
        $args = func_get_args();

        echo static::$_cssPipe->serve(end($args), array_slice($args, 0, -1, true));
    }

    public static function less(/* func_get_args = (options…, filename) */)
    {
        $args = func_get_args();

        echo static::$_lessPipe->serve(end($args), array_slice($args, 0, -1, true));
    }

    public static function sass(/* func_get_args = (options…, filename) */)
    {
        $args = func_get_args();

        echo static::$_sassPipe->serve(end($args), array_slice($args, 0, -1, true));
    }
}