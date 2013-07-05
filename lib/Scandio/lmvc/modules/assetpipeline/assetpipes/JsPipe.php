<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

/**
 * Class JsPipe
 * @package Scandio\lmvc\modules\assetpipeline\assetpipes
 *
 * Handling all the js files to be processed.
 */
class JsPipe extends AbstractAssetPipe
{

    protected static
        $_contentType   = "js",
        $_pipeForType   = "js";

    function __construct()
    {
        parent::__construct();
    }

    private function _min($asset)
    {
        return \JSMinPlus::minify(file_get_contents($asset));
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