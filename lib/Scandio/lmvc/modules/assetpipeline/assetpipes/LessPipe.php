<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

/**
 * Class LessPipe
 * @package Scandio\lmvc\modules\assetpipeline\assetpipes
 *
 * Handles all Less files to be processed.
 */
class LessPipe extends AbstractAssetPipe
{

    protected static
        $_contentType   = "text/css";

    private
        $_lessCompiler;

    function __construct()
    {
        $this->_lessCompiler = new \lessc();

        parent::__construct();
    }

    private function _min($asset)
    {
        return \CssMin::minify(file_get_contents($asset));
    }

    private function _compile($asset)
    {
        return $this->_lessCompiler->compile(file_get_contents($asset));
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

        $css = $this->_compile($file);

        if (in_array('min', $options)) {
            $css = $this->_min($file);
        }

        return $css;
    }

}