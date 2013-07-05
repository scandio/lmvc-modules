<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

/**
 * Class CssPipe
 * @package Scandio\lmvc\modules\assetpipeline\assetpipes
 *
 * Handles Css files to be processed by pipe.
 */
class CssPipe extends AbstractAssetPipe
{

    protected
        $_contentType = "css";

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Minifies the contents of file given.
     *
     * @param $asset to be minified
     * @return string the minified stream
     */
    private function _min($asset)
    {
        #that's why composer is awesome
        return \CssMin::minify(file_get_contents($asset));
    }

    /**
     * The abstract process method to be called whenever file needs to be handled by this pipe.
     *
     * @param $asset which should be processed by this pipe
     * @param array $options to be applied on asset (e.g. min)
     *
     * @return string containing the processed file's content
     */
    public function process($asset, $options = [])
    {
        $css = null;
        $file = $this->_assetDirectory . DIRECTORY_SEPARATOR . $asset;

        #needs no explanation?
        if (in_array('min', $options)) {
            $css = $this->_min($file);
        } else {
            $css = file_get_contents($file);
        }

        return $css;
    }

}