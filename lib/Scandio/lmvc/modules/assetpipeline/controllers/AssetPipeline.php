<?php

namespace Scandio\lmvc\modules\assetpipeline\controllers;

use Scandio\lmvc\LVC;
use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\Controller;
use Scandio\lmvc\modules\assetpipeline\interfaces;
use Scandio\lmvc\modules\assetpipeline\assetpipes;
use Scandio\lmvc\modules\assetpipeline\util;


class AssetPipeline extends Controller implements interfaces\AssetPipelineInterface
{
    private static
        $_pipes = [],
        $_flexOptions = [],
        $_reservedUrlKeywords = [],
        $_helper;

    protected static
        $config = [],
        $defaults = [
            'useFolders' => true,
            'stage' => 'dev',
            'cacheDirectory' => '_cache',
            'assetDirectories' => [
                'js' => [
                    'main'      => 'javascripts',
                    'fallbacks' => []
                ],
                'coffee' => [
                    'main'      => 'coffeescript',
                    'fallbacks' => []
                ],
                'less' => [
                    'main'      => 'styles',
                    'fallbacks' => []
                ],
                'sass' => [
                    'main'      => 'styles',
                    'fallbacks' => []
                ],
                'scss' => [
                    'main'      => 'styles',
                    'fallbacks' => []
                ],
                'css' => [
                    'main'      => 'styles',
                    'fallbacks' => []
                ],
                'img' => [
                    'main'      => 'img',
                    'fallbacks' => []
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
                static::$config['assetDirectories'][$type]['main'],
                static::$config['assetDirectories'][$type]['fallbacks'],
                static::$config['assetRootDirectory']
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

    public static function configure()
    {
        static::$config = array_replace_recursive(
            static::$defaults,
            static::$_helper->asArray(LVCConfig::get()->assetpipeline)
        );

        static::initialize();
    }

    public static function setRootDirectory($rootDirectory)
    {
        static::$config['assetRootDirectory'] = $rootDirectory;

        #creates all the pipes (http://cdn.meme.li/instances/300x300/39438036.jpg)
        static::_instantiatePipes();
    }

    public static function registerFlexOptions($options = [])
    {
        static::$_flexOptions = array_merge(static::$_flexOptions, $options);
    }

    public static function initialize()
    {
        #for any file locator set the stage
        util\FileLocator::setStage(static::$config['stage']);
    }

    public static function index($action)
    {
        #first is action
        $args = array_filter(
            array_merge(array_slice(func_get_args(), 1), static::$_flexOptions)
        );

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