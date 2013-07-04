<?php

namespace Scandio\lmvc\modules\assetpipeline\util;


class FileLocator
{
    private
        $_cacheDirectory,
        $_assetDirectory,
        $_cachedFileName,
        $_cachedFilePath,
        $_cachedFileInfo,
        $_uncachedFileObject;

    function __construct($cacheDirectory = "", $assetDirectory = "") {
        $this->_cacheDirectory = $cacheDirectory;
        $this->_assetDirectory = $assetDirectory;
    }

    protected function concat() {

    }

    public function initializeCache($asset, $options = []) {
        if ( !file_exists($this->_assetDirectory . DIRECTORY_SEPARATOR . $asset)) return false;

        $this->_cachedFilePath    = $this->_assetDirectory . DIRECTORY_SEPARATOR . $this->_cacheDirectory;
        $this->_cachedFileName    = ( count($options) > 0 ) ? implode(".", $options) . "." . $asset : $asset;

        $this->_cachedFileInfo  = new \SplFileInfo($this->_cachedFilePath . DIRECTORY_SEPARATOR . $this->_cachedFileName);
        $this->_ordinaryFileObject  = new \SplFileObject($this->_assetDirectory . DIRECTORY_SEPARATOR . $asset, "r");

        return true;
    }

    public function isCached() {
        return $this->_cachedFileInfo->isFile() && ( $this->_ordinaryFileObject->getMTime() < $this->_cachedFileInfo->getMTime() );
    }

    public function fromCache() {
        return file_get_contents( $this->_cachedFileInfo->getPathname() );
    }

    public function cache($fileContent) {
        $cachedFileObject  = new \SplFileObject($this->_cachedFilePath . DIRECTORY_SEPARATOR . $this->_cachedFileName, "w+");

        $cachedFileObject->fwrite($fileContent);

        return $fileContent;
    }

    public function setCacheDirectory($cacheDirectory) {
        $this->_cacheDirectory = $cacheDirectory;
    }

    public function setAssetDirectory($assetDirectory) {
        $this->_assetDirectory = $assetDirectory;
    }
}