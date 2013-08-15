<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

/**
 * Class SassPipe
 * @package Scandio\lmvc\modules\assetpipeline\assetpipes
 *
 * Pipe responsible for Sass files.
 */
class SassPipe extends AbstractAssetPipe
{

    protected static
        $_contentType   = "text/css";

    private
        $_sassCompiler;

    function __construct()
    {
        $this->_sassCompiler = new \scssc();

        parent::__construct();
    }

    private function _min($asset)
    {
        if (!$this->_hasDefaultMimeType($asset)) {
            return \CssMin::minify(file_get_contents($asset));
        } else {
            return file_get_contents($asset);
        }
    }

    private function _compile($asset)
    {
        return $this->_sassCompiler->compile(file_get_contents($asset));
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

        if (!$this->_hasDefaultMimeType($asset)) {
            $css = $this->_compile($file);

            if (in_array('min', $options)) {
                $css = $this->_min($file);
            }
        } else {
            $css = file_get_contents($file);
        }

        return $css;
    }

}