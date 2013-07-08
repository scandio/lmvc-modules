<?php

namespace Scandio\lmvc\modules\assetpipeline\controllers;

use Scandio\lmvc\LVC;
use Scandio\lmvc\Controller;
use Scandio\lmvc\modules\assetpipeline\interfaces;
use Scandio\lmvc\modules\assetpipeline\assetpipes;
use Scandio\lmvc\modules\assetpipeline\util;


class AssetPipeline extends Controller implements interfaces\AssetPipelineInterface
{
    private static
        $_pipes = [],
        $_helper;

    protected static
        $config = [],
        $defaults = [
        'stage' => 'dev',
        'assetRootDirectory' => '',
        'cacheDirectory' => '_cache',
        'assetDirectories' => [
            'js' => [
                'main' => 'javascripts'
            ],
            'less' => [
                'main' => 'styles'
            ],
            'sass' => [
                'main' => 'styles'
            ],
            'scss' => [
                'main' => 'styles'
            ],
            'css' => [
                'main' => 'styles'
            ]
        ]
    ];

    function __construct()
    {
        static::$_helper = new util\AssetPipelineHelper();
    }

    private static function _instantiatePipes()
    {
        #create all the pipes for types - all double nested
        foreach (static::$_pipes as $type => $pipe) {
            static::$_pipes[$type] = new $pipe;

            #set some settings on pipes dependend on their type (array-reference) which may be fragile but works for now
            static::$_pipes[$type]->setCacheDirectory(static::$config['cacheDirectory']);

            static::$_pipes[$type]->setAssetDirectory(
                static::$_helper->path([static::$config['assetRootDirectory'], static::$config['assetDirectories'][$type]['main']]),

                static::$_helper->prefix(isset(static::$config['assetDirectories'][$type]['fallbacks']) ?
                                            static::$config['assetDirectories'][$type]['fallbacks'] :
                                            [],
                static::$config['assetRootDirectory'])
            );
        }
    }

    public static function registerAssetpipe($types, $pipe)
    {
        #multiple pipes per type possible although last of type wins because multiple pipes per type would lead
        #to unpredictable outcomes due to order imho
        foreach ($types as $type) {
            static::$_pipes[$type] = $pipe;
        }
    }

    public static function configure($config = [])
    {
        static::$config = array_replace_recursive(static::$defaults, $config);

        static::initialize();
    }

    public static function initialize()
    {
        #for any file locator set the stage
        util\FileLocator::setStage(static::$config['stage']);

        #creates all the pipes (http://cdn.meme.li/instances/300x300/39438036.jpg)
        static::_instantiatePipes();
    }

    public static function index($action, $params)
    {
        echo "< Please specify a pipe as action as in: css|js|sass|less >";
    }

    public static function js( /* func_get_args = (options…, filenames…) */)
    {
        $args = func_get_args();

        echo static::$_pipes['js']->serve(static::$_helper->getFiles($args), static::$_helper->getOptions($args));
    }

    public static function css( /* func_get_args = (options…, filenames…) */)
    {
        $args = func_get_args();

        echo static::$_pipes['css']->serve(static::$_helper->getFiles($args), static::$_helper->getOptions($args));
    }

    public static function less( /* func_get_args = (options…, filenames…) */)
    {
        $args = func_get_args();

        echo static::$_pipes['less']->serve(static::$_helper->getFiles($args), static::$_helper->getOptions($args));
    }

    public static function sass( /* func_get_args = (options…, filenames…) */)
    {
        $args = func_get_args();

        echo static::$_pipes['sass']->serve(static::$_helper->getFiles($args), static::$_helper->getOptions($args));
    }

    public static function scss( /* func_get_args = (options…, filenames…) */)
    {
        $args = func_get_args();

        echo static::$_pipes['sass']->serve(static::$_helper->getFiles($args), static::$_helper->getOptions($args));
    }
}