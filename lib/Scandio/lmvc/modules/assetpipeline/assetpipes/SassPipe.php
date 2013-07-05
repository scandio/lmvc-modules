<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

class SassPipe extends AbstractAssetPipe {

    protected
        $_contentType = "css";

    private
        $_sassCompiler;

    function __construct() {
        $this->_sassCompiler = new \scssc();

        parent::__construct();
    }

    private function _min($asset) {
        return \CssMin::minify(file_get_contents($asset));
    }

    private function _compile($asset) {
        return $this->_sassCompiler->compile(file_get_contents($asset));
    }

    public function process($asset, $options = []) {
        $css    = null;
        $file   = $this->_assetDirectory . DIRECTORY_SEPARATOR . $asset;

        $css = $this->_compile($file);

        if ( in_array('min', $options) ) { $css = $this->_min($file); }

        return $css;
    }

}