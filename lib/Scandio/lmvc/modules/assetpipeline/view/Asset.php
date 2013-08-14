<?php

namespace Scandio\lmvc\modules\assetpipeline\view;

use Scandio\lmvc\LVC;

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
     * @param string $path for asset(s) to be requested (same for all)
     * @param array $options for asset pipeline as e.g. ['min'] for asset minification
     *
     * @return string the URI to the requested asset(s)
     */
    public static function assets($assets, $path = '', $options = array())
    {
        # Determines pipe by last file's extension, different files in one request is madness
        $pipe = pathinfo($assets[count($assets) - 1], PATHINFO_EXTENSION);

        # Return the url with its options
        return LVC::get()->url('asset-pipeline::' . $pipe, array_merge($options, array($path), $assets));
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
}