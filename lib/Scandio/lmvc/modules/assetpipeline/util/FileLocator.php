<?php

namespace Scandio\lmvc\modules\assetpipeline\util;

use Scandio\lmvc\modules\assetpipeline\util;

class FileLocator
{
    private
        $_helper,
        $_cacheDirectory,
        $_assetDirectory,
        $_cachedFileName,
        $_cachedFilePath,
        $_cachedFileInfo,
        $_requestedFiles = [];

    function __construct($cacheDirectory = "", $assetDirectory = "") {
        $this->_cacheDirectory = $cacheDirectory;
        $this->_assetDirectory = $assetDirectory;

        $this->_helper = new util\AssetPipelineHelper();
    }

    private function _setCachedFilePath() {
        $this->_cachedFilePath = $this->_assetDirectory . DIRECTORY_SEPARATOR . $this->_cacheDirectory;
    }

    private function _getCachedFileName($assets, $options) {
        $fileName = "";

        #Prefix with options e.g: min.00898888222. (dot in in end!)
        $fileName = ( count($options) > 0 ) ? implode(".", $options) . "." : "";

        #Append file names with + as delimiter and remove extensions from all except last file (e.g. [min.929292.]jquery+my-plugin.js
        $fileName .= implode("+", $this->_helper->stripExtensions($assets, true));

        return $fileName;
    }

    public function initializeCache($assets, $options = []) {
        # Requesting an non-existent file should return 404 or ane empty file.
        #if ( !file_exists($this->_assetDirectory . DIRECTORY_SEPARATOR . $asset)) return false;

        $this->_cachedFileName = $this->_getCachedFileName($assets, $options);
        $this->_cachedFileInfo = new \SplFileInfo( $this->_cachedFilePath . DIRECTORY_SEPARATOR . $this->_cachedFileName );

        foreach ($assets as $asset) {
            $this->_requestedFiles[] = new \SplFileObject($this->_assetDirectory . DIRECTORY_SEPARATOR . $asset, "r");
        }

        return true;
    }

    public function isCached() {
        foreach ($this->_requestedFiles as $requestedFile) {
            if (! $this->_cachedFileInfo->isFile() || ( $requestedFile->getMTime() > $this->_cachedFileInfo->getMTime() )) {
                return false;
            }
        }

        return true;
    }

    public function fromCache() {
        return file_get_contents( $this->_cachedFileInfo->getPathname() );
    }

    public function concat($assets) {
        $fileContent = "";

        foreach ($assets as $asset) {
            $fileLocation = $this->_assetDirectory . DIRECTORY_SEPARATOR . $asset;

            $fileContent .= file_get_contents($fileLocation);
        }

        $this->cache($fileContent);

        return $this->_cachedFilePath . DIRECTORY_SEPARATOR . $this->_cachedFileName;
    }

    public function cache($fileContent) {
        $cachedFileObject  = new \SplFileObject($this->_cachedFilePath . DIRECTORY_SEPARATOR . $this->_cachedFileName, "w+");

        $cachedFileObject->fwrite($fileContent);

        return $fileContent;
    }

    public function setCacheDirectory($cacheDirectory) {
        $this->_cacheDirectory = $cacheDirectory;

        $this->_setCachedFilePath();
    }

    public function setAssetDirectory($assetDirectory) {
        $this->_assetDirectory = $assetDirectory;

        $this->_setCachedFilePath();
    }
}