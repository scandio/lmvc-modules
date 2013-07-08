<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

use Scandio\lmvc\LVC;
use Intervention\Image\Image;

/**
 * Class ImagePipe
 * @package Scandio\lmvc\modules\assetpipeline\assetpipes
 *
 * Handles Image files to be resized etc.
 */
class ImagePipe extends AbstractAssetPipe
{

    protected static
        $_contentType   = "image/";

    function __construct()
    {
        parent::__construct();
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
        $file = $this->_assetDirectory . DIRECTORY_SEPARATOR . $asset;

        $img = Image::make($asset);

        $img->resize(LVC::get()->request->w, LVC::get()->request->h);

        $img->save($asset);

        #just piping it for output
        return $img;
    }

    /**
     * Sets the response content-type appropriately so that browsers display content correctly.
     */
    protected function _setHttpHeaders()
    {
        header("Content-Type: " . pathinfo($asset, PATHINFO_EXTENSION));
    }

}