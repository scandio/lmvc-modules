<?php

namespace Scandio\lmvc\modules\assetpipeline\util;

/**
 * Class AssetPipelineHelper
 * @package Scandio\lmvc\modules\assetpipeline\util
 *
 * Provides a set of common helper functions not fitting into the class directly used by any component needing them.
 */
class AssetPipelineHelper
{
    /**
     * Filters an array returning only the file names or options passed.
     *
     * @param array $args to be filtered, normally accessed via func_get_args
     * @param bool $onlyFiles flag indicating if files|options shall be returned.
     *
     * @return array filtered array depending on $onlyFiles flag
     */
    private function _filterArgs($args, $onlyFiles = true)
    {
        $filtered = [];

        foreach ($args as $arg) {
            #regex to determine if arg is a file
            if (preg_match('=^[^/?*;:{}\\\\]+\.[^/?*;:{}\\\\]+$=', $arg)) {
                if (!$onlyFiles) continue;

                $filtered[] = $arg;
            } else if (!$onlyFiles) {
                $filtered[] = $arg;
            }
        }

        return $filtered;
    }

    /**
     * Generates a path by giving an array of directories
     *
     * @param array $directories of directories to be imploded into string
     * @return string representing the directories passed in the array
     */
    public function path($directories)
    {
        return implode(DIRECTORY_SEPARATOR, $directories);
    }

    /**
     * Prefixes all elements in an array with a given prefix.
     *
     * @param array $directories to be prefixed
     * @param string $with used to prefix all items
     *
     * @return array of items all prefixed by $with argument
     */
    public function prefix($directories, $with)
    {
        $prefixed = [];

        foreach ($directories as $directory) {
            $prefixed[] = $this->path([$with, $directory]);
        }

        return $prefixed;
    }

    /**
     * Filters array to only contain options or strip all file names.
     *
     * @param array $args to be filtered
     * @return array containing only options filtered from $args
     */
    public function getOptions($args)
    {
        return $this->_filterArgs($args, false);
    }

    /**
     * Filters array to contain only file names or no options
     *
     * @param array $args to be filtered
     * @return array containing only file names
     */
    public function getFiles($args)
    {
        return $this->_filterArgs($args, true);
    }

    /**
     * Strips extensions from all files passed in array.
     *
     * @param array $fileNames
     * @param bool $exceptLast if true leaves extension on last file
     *
     * @return array of items with stripped extensions (only basepath)
     */
    public function stripExtensions($fileNames, $exceptLast = true)
    {
        $strippedFileNames = [];
        $lastKey = key(array_slice($fileNames, -1, 1, true));

        foreach ($fileNames as $key => $fileName) {
            if ($exceptLast && $key == $lastKey) {
                $strippedFileNames[] = $fileName;
                continue;
            }

            #remove the .something
            $strippedFileNames[] = substr($fileName, 0, strrpos($fileName, '.'));
        }

        return $strippedFileNames;
    }
}