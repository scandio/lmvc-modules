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
        $_reservedUrlKeywords = [],
        $_helper;

    protected static
        $config = [],
        $defaults = [
        'useFolders' => true,
        'stage' => 'dev',
        'assetRootDirectory' => '',
        'cacheDirectory' => '_cache',
        'assetDirectories' => [
            'js' => [
                'main' => 'javascripts'
            ],
            'coffee' => [
                'main' => 'coffeescript'
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
            ],
            'img' => [
                'main' => 'img'
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

            static::$_pipes[$type]->useFolders(static::$config['useFolders']);

            static::$_pipes[$type]->setAssetDirectory(
                static::$_helper->path([static::$config['assetRootDirectory'], static::$config['assetDirectories'][$type]['main']]),

                static::$_helper->prefix(isset(static::$config['assetDirectories'][$type]['fallbacks']) ?
                                            static::$config['assetDirectories'][$type]['fallbacks'] :
                                            [],
                static::$config['assetRootDirectory'])
            );
        }
    }

    public static function registerAssetpipe($types, $pipe, $options = [])
    {
        #notifiy helper about new reserved keywords which are options and types
        util\AssetPipelineHelper::addReservedKeywords($options);
        util\AssetPipelineHelper::addReservedKeywords($types);

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

    public static function index($action)
    {
        #first is action
        $args = array_filter(array_slice(func_get_args(), 1));

        if(array_key_exists($action, static::$_pipes)) {
            echo static::$_pipes[$action]->serve(
                static::$_helper->getFiles($args),
                static::$_helper->getPaths($args),
                static::$_helper->getOptions($args)
            );
        } else {
            echo "< Please specify a pipe as action as in: " . implode(" | ", array_keys(static::$_pipes)) . " >";
        }
    }
}