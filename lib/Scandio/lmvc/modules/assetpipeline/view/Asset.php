<?php

namespace Scandio\lmvc\modules\assetpipeline\view;

use Scandio\lmvc\LVC;
use Scandio\lmvc\modules\assetpipeline\controllers\AssetPipeline;

class Asset
{
    /**
     * Simple connection to the asset pipeline module.
     * returns/parses a url to the assets specified in array and option which will be handled by the
     * responsible pipe if the asset pipeline itself is loaded as a module during the request.
     *
     * Note: If the asset pipeline is inactive the call-routing can only handle a string!
     *
     * @param array|array $assets containing asset(s) as in ['jquery.js', 'myplugin.js'] (will be concatenated)
     * @param array $options for asset pipeline (path and other) defaults to ['min'] for asset minification in prod-mode and nulled path
     *
     * @return string the URI to the requested asset(s)
     */
    public static function assets($assets, $options = [])
    {
        # Determines pipe by last file's extension, different files in one request is madness
        $pipe = pathinfo($assets[count($assets) - 1], PATHINFO_EXTENSION);

        $assets = implode("+", $assets);

        if (AssetPipeline::getConfig()['stage'] === 'prod') {
            $options[] = 'min';
        }

        # Return the url with its options
        return LVC::get()->url('asset-pipeline::' . $pipe, array_merge($options, array($assets)));
    }

    /**
     * Simple connection to the asset pipeline image module.
     * Allowing for automatic image resizing.
     *
     * @param array $img stating image to be loaded
     * @param array $options for e.g. w and h of image (['w' => 800, 'h' => 600], scales proportionally)
     *
     * @return string the URI to the requested asset(s)
     */
    public static function image($img, $options = array())
    {
        # Parses param to array, no type check needed
        $img = (array) $img;

        # Builds query string from options array
        $queryString = "?" . http_build_query($options);

        # Return the url with its options
        return LVC::get()->url('asset-pipeline::img', implode(DIRECTORY_SEPARATOR, $img)) . $queryString;
    }

    /**
     * Simple connection to the asset pipeline markdown module.
     *
     * @param array $file markdown file to be compiled
     * @param array $options containing path and other additionally desired options
     *
     * @return string the rendered html from markdown source (maybe cached)
     */
    public static function markdown($file, $options = array(), $content = false)
    {
        $url = LVC::get()->url('asset-pipeline::markdown', array_merge($options, array($assets)));

        return $content ? file_get_contents($url) : $url;
    }
}