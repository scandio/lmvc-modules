<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

use Scandio\lmvc\modules\assetpipeline\interfaces;
use Scandio\lmvc\modules\assetpipeline\util;

abstract class AbstractAssetPipe implements interfaces\iAssetPipe {

    protected
        $_contentType,
        $_fileLocator;

    abstract public function process($asset, $options = []);

    function __construct() {
        $this->_fileLocator = new util\FileLocator();
    }

    private function _setHttpHeaders() {
        header("Content-Type: text/" . $this->_contentType);
    }

    public function serve($asset, $options = []) {
        $servedContent = "";

        $this->_setHttpHeaders();

        if ( $this->_fileLocator->initializeCache($asset, $options) ) {
            $servedContent = $this->_fileLocator->isCached() ? $this->_fileLocator->fromCache() : $this->_fileLocator->cache( $this->process($asset, $options) );
        }

        return $servedContent;
    }

    public function setCacheDirectory($cacheDirectory) {
        $this->_fileLocator->setCacheDirectory($cacheDirectory);
    }

    public function setAssetDirectory($assetDirectory) {
        $this->_fileLocator->setAssetDirectory($assetDirectory);
    }
}