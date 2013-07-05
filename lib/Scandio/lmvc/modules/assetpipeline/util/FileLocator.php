<?php

namespace Scandio\lmvc\modules\assetpipeline\util;

use Scandio\lmvc\modules\assetpipeline\util;

class FileLocator
{
    private
        $_helper,
        $_cacheDirectory,
        $_assetDirectory,
        $_assetDirectoryFallbacks,
        $_cachedFileName,
        $_cachedFilePath,
        $_cachedFileInfo,
        $_requestedFiles = [];

    private static
        $_stage;

    function __construct($cacheDirectory = "", $assetDirectory = "") {
        $this->_cacheDirectory = $cacheDirectory;
        $this->_assetDirectory = $assetDirectory;

        $this->_helper = new util\AssetPipelineHelper();
    }

    private function _setCachedFilePath() {
        $this->_cachedFilePath = $this->_helper->path([$this->_assetDirectory, $this->_cacheDirectory]);
    }

    private function _getCachedFileName($assets, $options) {
        $fileName = "";

        #Prefix with options e.g: min.00898888222. (dot in in end!)
        $fileName = ( count($options) > 0 ) ? implode(".", $options) . "." : "";

        #Append file names with + as delimiter and remove extensions from all except last file (e.g. [min.929292.]jquery+my-plugin.js
        $fileName .= implode("+", $this->_helper->stripExtensions($assets, true));

        return $fileName;
    }

    private function _recursiveSearch($asset) {
        $fileLocation = false;

        foreach ($this->_assetDirectoryFallbacks as $assetDirectoryFallback) {
            $directoryIterator = new \RecursiveDirectoryIterator($assetDirectoryFallback);
            $iteratorIterator = new \RecursiveIteratorIterator($directoryIterator);

            foreach($iteratorIterator as $possibleFile) {
                if ($asset == $possibleFile->getFileName()) {
                    $fileLocation = $possibleFile->getPathname();
                    break 2;
                }
            }
        }

        return $fileLocation;
    }

    private function _forceCache() {
        if (static::$_stage == 'prod') { return true; }

        return false;
    }

    public static function setStage($stage) {
        static::$_stage = $stage;
    }

    public function initializeCache($assets, $options = []) {
        $this->_cachedFileName = $this->_getCachedFileName($assets, $options);
        $this->_cachedFileInfo = new \SplFileInfo( $this->_helper->path([$this->_cachedFilePath, $this->_cachedFileName]) );

        foreach ($assets as $asset) {
            # Requesting an non-existent file searches fallback dirs
            $assetFilePath = $this->_helper->path([$this->_assetDirectory, $asset]);

            if ( file_exists( $assetFilePath ) ) {
                $this->_requestedFiles[] = new \SplFileObject($assetFilePath, "r");

            } else if ($assetFilePath = $this->_recursiveSearch($asset)) {
                $this->_requestedFiles[] = new \SplFileObject($assetFilePath, "r");
            }
            else {
                return false;
            }
        }

        return true;
    }

    public function isCached() {
        if ($this->_cachedFileInfo->isFile() && $this->_forceCache()) { return true; }

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

    public function concat() {
        foreach ($this->_requestedFiles as $requestedFile) {
            $this->cache( file_get_contents($requestedFile->getPathname()), true);
        }

        return $this->_helper->path([$this->_cachedFilePath, $this->_cachedFileName]);
    }

    public function cache($fileContent, $append = false) {
        $cachedFileObject  = new \SplFileObject($this->_helper->path([$this->_cachedFilePath, $this->_cachedFileName]), $append ? "a+" : "w+");

        $cachedFileObject->fwrite($fileContent);

        return $fileContent;
    }

    public function setCacheDirectory($cacheDirectory) {
        $this->_cacheDirectory = $cacheDirectory;

        $this->_setCachedFilePath();
    }

    public function setAssetDirectory($assetDirectory, $fallbacks = []) {
        $this->_assetDirectory          = $assetDirectory;
        $this->_assetDirectoryFallbacks = $fallbacks;

        $this->_setCachedFilePath();
    }
}