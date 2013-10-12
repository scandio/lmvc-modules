<?php

namespace Scandio\lmvc\modules\assetpipeline\util;

use Scandio\lmvc\modules\assetpipeline\util;
use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\LVC;

/**
 * Class FileLocator
 * @package Scandio\lmvc\modules\assetpipeline\util
 *
 * Component encapsulating all operations on files in asset pipeline.
 * Such as writing/reading from cache or determining if recompilation of asset is needed.
 */
class FileLocator
{
    private
        $_helper,
        $_useFolders,
        $_cacheDirectory,
        $_assetDirectory,
        $_assetDirectoryFallbacks,
        $_cachedFileName,
        $_cachedFilePath,
        $_cachedFileInfo,
        $_requestedFiles = [],
        $_unFoundAssets = [];

    private static
        $_stage,
        $_aggressiveCaching;

    /**
     * Constructs FileLocator acting according to parameters.
     *
     * @param string $cacheDirectory the cache-directory name to be used
     * @param string $assetDirectory the orginary asset directory where raw assets reside
     */
    function __construct($cacheDirectory = "", $assetDirectory = "")
    {
        $this->_cacheDirectory = $cacheDirectory;
        $this->_assetDirectory = $assetDirectory;

        $this->_helper = new util\AssetPipelineHelper();
    }

    /**
     * Sets the cache's file path for the asset.
     * Basically the asset-directory + cache directory.
     */
    private function _setCachedFilePath()
    {
        $this->_cachedFilePath = $this->_helper->path([$this->_assetDirectory, $this->_cacheDirectory]);
    }

    /**
     * Returns the file name path for a set of to be cached assets with options.
     *
     * @param array $assets to be combined into one as a concatenated file
     * @param array $options passed being incorporated into file name (appended) to reference file uniquely
     *
     * @return string file name of cached asset e.g. jquery+myplugin.js
     */
    private function _getCachedFileName($assets, $paths, $options, $delimiter = "+")
    {
        $fileName = "";

        # Trim file identifiers (filename, path and options) to max of 35 characters (uses sha-1 for unique idenfication)
        # Ternary used to append separator for next part of 'slug'
        $pathImploded       = ( count($paths) > 0 ) ? implode($delimiter, $paths) . "-" : "";
        $fileImploded       = implode($delimiter, $this->_helper->stripExtensions($assets, true));

        $optionsIdentifier  = implode(".", $options) . ".";
        $pathIdentifier     = substr($pathImploded, 0, 8);
        $fileIdentifier     = substr($fileImploded, -20);
        $hashIdentifier     = substr( sha1($pathImploded . $fileImploded . $optionsIdentifier), 0 , 7 ) . "-";

        $fileName = $hashIdentifier . $optionsIdentifier . $pathIdentifier . $fileIdentifier;

        return $fileName;
    }

    /**
     * Recursively searches for an asset by file name in all fallback directories set.
     *
     * @param string $asset requested which was unfound in asset directory
     *
     * @return bool indicating if search was successful
     */
    private function _recursiveSearch($asset)
    {
        # Prevents from fudgy input on fallback-directories
        if (!is_array($this->_assetDirectoryFallbacks)) { return false; }

        $fileLocation = false;
        #Enter iterator madness: for every fallback given for pipeline
        foreach ($this->_assetDirectoryFallbacks as $assetDirectoryFallback) {
            #generate an directory iterator and an iterator-iterator to recursively traverse tree
            $directoryIterator = new \RecursiveDirectoryIterator($assetDirectoryFallback);
            $iteratorIterator = new \RecursiveIteratorIterator($directoryIterator);

            #checks if file found is requested one
            foreach ($iteratorIterator as $possibleFile) {
                #if file name match
                if ($asset == $possibleFile->getFileName()) {
                    #break all the loops (http://cdn.meme.li/instances/300x300/39435708.jpg)
                    $fileLocation = $possibleFile->getPathname();

                    break 2;
                }
            }
        }

        return $fileLocation;
    }

    /**
     * Indicates if cache-forcing shall be used which ignores any timestamp comparisons.
     *
     * Note: Tightly coupled to configuration values. Nevertheless, this allows for more flexibility.
     *
     * @return bool indicating if caching shall be forced.
     */
    private function _forceCache()
    {
        #currently only force caching on $stage == 'prod'
        if (static::$_stage == 'prod') {
            return true;
        }

        return false;
    }

    /**
     * Finds all files contained in one directory.
     *
     * @param string $path for which all files need to be found
     *
     * @return array containing all file names in path
     */
    private function _allFiles($path) {
        $assets = [];

        #only do work if folders are enabled
        if ($this->_useFolders === false) return $assets;

        $directoryIterator = new \DirectoryIterator($this->_helper->path([$this->_assetDirectory, $path]));

        foreach ($directoryIterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $assets[] = $fileInfo->getFilename();
            }
        }

        return $assets;
    }

    /**
     * Sets the stage in which the software is running in.
     *
     * @param string $stage of production the software is in.
     */
    public static function setStage($stage)
    {
        static::$_stage = $stage;
    }

    /**
     * Sets up 304 caching to be used if demanded.
     *
     * @param boolean $flag indicating caching wish.
     */
    public static function set304Caching($flag)
    {
        static::$_aggressiveCaching = $flag;
    }

    /**
     * @param boolean $flag indicating if sub directories should be used
     */
    public function useFolders($flag) {
        $this->_useFolders = $flag;
    }

    /**
     * @param array $assets to be cached and which are requested.
     * @param array $options which are applied on assets.
     *
     * @return array of assets which where loaded into cache
     */
    public function initializeCache($assets, $paths, $options = [])
    {
        #Either files are requested or all files from a directory need to be found
        $assets = (count($assets) > 0) ? $assets : $this->_allFiles($paths);

        #Get the file name for cached file
        $this->_cachedFileName = $this->_getCachedFileName($assets, $paths, $options);
        #and only file info for it because no writing/reading needed in all cases
        $this->_cachedFileInfo = new \SplFileInfo($this->_helper->path([$this->_cachedFilePath, $this->_cachedFileName]));

        foreach ($assets as $asset) {
            #At first asset may be under its asset directory
            $assetFilePath = $this->_helper->path(
                [$this->_assetDirectory, $paths, $asset]
            );

            #if it is return true
            if (file_exists($assetFilePath)) {
                $fileObject = new \SplFileObject($assetFilePath, "r");
                $this->_requestedFiles[] = $fileObject;

            #still it may be found only in fallback dirs
            } else if ($assetFilePath = $this->_recursiveSearch($asset)) {
                $fileObject = new \SplFileObject($assetFilePath, "r");
                $this->_requestedFiles[] = $fileObject;

            #or non-existent
            } else {
                http_response_code(404);
                # Needed for denying cache
                $this->_requestedFiles[] = false;
                # For error logging of unfound assets
                $this->_unFoundAssets[] = $asset;
                # Up to next asset
                continue;
            }
        }

        return $assets;
    }

    /**
     * Gets errors which occured during asset-file finding process
     *
     * @return string somehow describing errors
     */
    public function getErrors() {
        $errors = '';

        if (count($this->_unFoundAssets) > 0) {
            $errors .=
                "AssetPipeline Error - unfound assets in main and fallback directories:\n - " .
                implode("\n - ", $this->_unFoundAssets);
        }

        return $errors;
    }

    /**
     * Checks if requested assets are cached and unchanged. Therefore determines if recompilation needed.
     *
     * @return bool indicating is asset(s) are cached or if the need to be recompiled and cached
     */
    public function isCached()
    {
        #break early if cached file existent and cache shall be forced
        if ($this->_cachedFileInfo->isFile() && $this->_forceCache()) {
            return true;
        }

        #if not do some work for every file
        foreach ($this->_requestedFiles as $requestedFile) {
            #but not cached if cached file non-existent or ordinary file has been after last cached version has been created
            if ($requestedFile === false || !$this->_cachedFileInfo->isFile() || ($requestedFile->getMTime() > $this->_cachedFileInfo->getMTime())) {
                return false;
            }
        }

        return true;
    }

    /**
     * Reads the cached file's content and returns it.
     *
     * Note: Returning a string is not probably the best idea: suggestions appreciated.
     *
     * @return string file content which was read from cache.
     */
    public function fromCache()
    {
        if (static::$_aggressiveCaching === true && !$this->_setHttpCacheHeaders( $this->_cachedFileInfo->openFile("r") )) {
            return file_get_contents($this->_cachedFileInfo->getPathname());
        }
    }

    /**
     * Concats the currently loaded set of assets into one file and returns the path of cached file.
     *
     * @return string path name from which concatenated asset can be loaded.
     */
    public function concat()
    {
        #empty the cache first
        $this->cache('', false);

        #cache all the requested files into one
        foreach ($this->_requestedFiles as $requestedFile) {
            if ($requestedFile !== false) {
                $this->cache(file_get_contents($requestedFile->getPathname()), true);
            }
        }

        #and return the file path
        return $this->_helper->path([$this->_cachedFilePath, $this->_cachedFileName]);
    }

    /**
     * Caches stream given and returns it. Option to append or replace to file's content.
     *
     * @param string $fileContent to be written into cache
     * @param bool $append indicating if content shall be appended (for internal use to concat files)
     *
     * @return string file content which has been cached for chaining.
     */
    public function cache($fileContent, $append = false)
    {
        #create file object with mode according to $append option
        $cachedFileObject = new \SplFileObject($this->_helper->path([$this->_cachedFilePath, $this->_cachedFileName]), $append ? "a+" : "w+");

        #and write to it
        $cachedFileObject->fwrite($fileContent);

        #finally return the content which has just been piped through
        return $fileContent;
    }

    /**
     * Sets the cache directory for this file locator.
     *
     * @param string $cacheDirectory to be used after setting the new directory.
     */
    public function setCacheDirectory($cacheDirectory)
    {
        $this->_cacheDirectory = $cacheDirectory;

        $this->_setCachedFilePath();
    }

    /**
     * Sets the asset directory to be used for this file locator.
     *
     * @param string $assetDirectory which should be used form now on
     * @param array $fallbacks to be used for searching for asset if file ca not be found in "root"
     */
    public function setAssetDirectory($assetDirectory, $fallbacks = [], $assetRootDirectory)
    {
        $fallbacks = is_array($fallbacks) ? $fallbacks : [];

        $this->_assetDirectory = $this->_helper->path([$assetRootDirectory, $assetDirectory]);

        foreach ($fallbacks as $fallback) {
            $this->_assetDirectoryFallbacks[] = $this->_helper->path([$assetRootDirectory, $fallback]);
        }

        $this->_setCachedFilePath();
    }

    /**
     * Sets http headers for proper caching with 304-code according to the file's change data.
     *
     * @param ojbect $fileObject    the fileObject who's change date should be compared against the
     *                              browser's cache-timestsamp
     *
     * @return bool indicating if file is cached in browser
     */
    private function _setHttpCacheHeaders($fileObject)
    {
        # Collect some information about file requested and file in browser's cache
        $fileModifiedTimestamp      = $fileObject->getMTime();
        $fileName                   = $fileObject->getFileName();
        $browserModifiedTimestamp   = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
                                        strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) : null;

        # Makes the browser respond with HTTP_IF_MODIFIED_SINCE and If-None-Match for E-Tag validation
        header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $fileModifiedTimestamp ) . " GMT");
        header('ETag: "'.md5($fileModifiedTimestamp.$fileName).'"');
        header('Cache-Control: public');

        # Now check if file content needs to be send
        if($browserModifiedTimestamp !== null && $fileModifiedTimestamp <= $browserModifiedTimestamp) {
            http_response_code(304);

            return true;
        }

        return false;
    }
}