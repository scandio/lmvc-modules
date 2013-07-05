<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

class JsPipe extends AbstractAssetPipe
{

    protected
        $_contentType = "js";

    function __construct()
    {
        parent::__construct();
    }

    private function _min($asset)
    {
        return \JSMinPlus::minify(file_get_contents($asset));
    }

    public function process($asset, $options = [])
    {
        $css = null;
        $file = $this->_assetDirectory . DIRECTORY_SEPARATOR . $asset;

        if (in_array('min', $options)) {
            $css = $this->_min($file);
        } else {
            $css = file_get_contents($file);
        }

        return $css;
    }
}