<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

use Scandio\lmvc\modules\assetpipeline\interfaces;
use Scandio\lmvc\modules\assetpipeline\util;

/**
 * Class AbstractAssetPipe
 * @package Scandio\lmvc\modules\assetpipeline\assetpipes
 *
 * Abstract asset pipeline to implement a set of interface functions while declaring process() abstract forcing each pipe
 * to implement it.
 *
 * After all, aggregating some functionality normally common for all pipes.
 */
abstract class AbstractAssetPipe implements interfaces\AssetPipeInterface
{

    protected
        $_contentType,
        $_fileLocator;

    #Each pipe is responsible for this
    abstract public function process($asset, $options = []);

    function __construct()
    {
        #each pipe uses its own file locator
        $this->_fileLocator = new util\FileLocator();
    }

    /**
     * Sets the response content-type appropriately so that browsers display content correctly.
     */
    private function _setHttpHeaders()
    {
        #Nothing more complicated up to now
        header("Content-Type: text/" . $this->_contentType);
    }

    /**
     * Gives stream of data which has been processed by pipe below in object-graph (css|js...-pipe).
     *
     * @param array $assets which should be processed by pipe
     * @param array $options to be possibly performed on assets
     *
     * @return string containing the stream which has been processed
     */
    public function serve($assets = [], $options = [])
    {
        $servedContent = "";

        $this->_setHttpHeaders();

        if ($this->_fileLocator->initializeCache($assets, $options)) {
            $servedContent = $this->_fileLocator->isCached() ?
                $this->_fileLocator->fromCache() :
                $this->_fileLocator->cache($this->process($this->_fileLocator->concat(), $options));
        }

        return $servedContent;
    }

    /**
     * Sets and delegates pipe's cache directory to file locator.
     *
     * @param string $cacheDirectory to be used for caching ressources
     */
    public function setCacheDirectory($cacheDirectory)
    {
        $this->_fileLocator->setCacheDirectory($cacheDirectory);
    }

    /**
     * Sets and delegates asset-directory and fallbacks to file locator.
     *
     * @param string $assetDirectory where ordinary assets can be found
     * @param array $fallbacks if nothing was found, all these fallbacks shall be used
     */
    public function setAssetDirectory($assetDirectory, $fallbacks = [])
    {
        $this->_fileLocator->setAssetDirectory($assetDirectory, $fallbacks);
    }
}