<?php

namespace Scandio\lmvc\modules\assetpipeline\interfaces;

/**
 * Class AssetPipeInterface
 * @package Scandio\lmvc\modules\assetpipeline\interfaces
 *
 * Defined a set of functions which have to be implemented by any asset pipe each handling one file type.
 */
interface AssetPipeInterface
{
    #where to cache
    public function setCacheDirectory($cacheDirectory);

    #where to find ordinary assets
    public function setAssetDirectory($assetDirectory);

    #called whenever the pipe needs to process some data
    public function process($asset, $options = []);

    #every pipe needs to register itself to the managing entity/controller
    public static function register($types);
}