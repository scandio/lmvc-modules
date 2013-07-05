<?php

namespace Scandio\lmvc\modules\assetpipeline\util;


class AssetPipelineHelper
{
    function __construct()
    {

    }

    private function _filterArgs($args, $onlyFiles = true)
    {
        $filtered = [];

        foreach ($args as $arg) {
            if (preg_match('=^[^/?*;:{}\\\\]+\.[^/?*;:{}\\\\]+$=', $arg)) {
                if (!$onlyFiles) continue;

                $filtered[] = $arg;
            } else if (!$onlyFiles) {
                $filtered[] = $arg;
            }
        }

        return $filtered;
    }

    public function path($directories)
    {
        $path = "";
        $lastKey = key(array_slice($directories, -1, 1, true));

        foreach ($directories as $key => $directory) {
            $path .= $directory;

            if ($key != $lastKey) {
                $path .= DIRECTORY_SEPARATOR;
            }
        }

        return $path;
    }

    public function prefix($directories, $with)
    {
        $prefixed = [];

        foreach ($directories as $directory) {
            $prefixed[] = $this->path([$with, $directory]);
        }

        return $prefixed;
    }

    public function getOptions($args)
    {
        return $this->_filterArgs($args, false);
    }

    public function getFiles($args)
    {
        return $this->_filterArgs($args, true);
    }

    public function stripExtensions($fileNames, $exceptLast = true)
    {
        $strippedFileNames = [];
        $lastKey = key(array_slice($fileNames, -1, 1, true));

        foreach ($fileNames as $key => $fileName) {
            if ($exceptLast && $key == $lastKey) {
                $strippedFileNames[] = $fileName;
                continue;
            }

            $strippedFileNames[] = substr($fileName, 0, strrpos($fileName, '.'));
        }

        return $strippedFileNames;
    }
}