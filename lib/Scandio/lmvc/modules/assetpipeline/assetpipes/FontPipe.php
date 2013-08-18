<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

/**
 * Class CssPipe
 * @package Scandio\lmvc\modules\assetpipeline\assetpipes
 *
 * Handles font files processed by pipe.
 */
class FontPipe extends AbstractAssetPipe
{

    protected static
        $_contentType   = "text/font";

    function __construct()
    {
        parent::__construct();
    }

    /**
     * The abstract process method to be called whenever file needs to be handled by this pipe.
     *
     * @param $asset which should be processed by this pipe
     * @param array $options to be applied on asset
     *
     * @return string containing the processed file's content
     */
    public function process($asset, $options = [])
    {
        $css = null;
        $file = $this->_assetDirectory . DIRECTORY_SEPARATOR . $asset;

        $font = file_get_contents($file);

        return $font;
    }

}