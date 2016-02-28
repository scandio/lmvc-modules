<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

use Scandio\lmvc\LVC;
use Intervention\Image\ImageManagerStatic as Image;

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

        //Image::configure(array('driver' => 'imagick'));
    }

    /**
     * The abstract process method to be called whenever file needs to be handled by this pipe.
     *
     * @param $asset which should be processed by this pipe
     * @param array $options to be applied on asset (e.g. min)
     * @param string describing errors during file location process
     *
     * @return string containing the processed file's content
     */
    public function process($asset, $options = [], $errors = '')
    {
        $img = Image::make($asset);

        if (isset($options[0]) && isset($options[1])) {
          $img->resize($options[0], $options[1], function($constraint) {
            $constraint->aspectRatio();
          });
        }

        $img->save($asset);

        #just piping it for output
        return $img;
    }

    /**
     * Sets the response content-type appropriately so that browsers display content correctly.
     */
    protected function _setHttpHeaders()
    {
        header("Content-Type: " . static::$_contentType . pathinfo($asset, PATHINFO_EXTENSION));
    }

    /**
     * The abstract comment method to be called whenever a comment shall be prepended to file
     *
     * @param $comment string being comment to be prepended
     * @param $toAssetContent string of processed file-content to which comment should be prepended
     *
     * @return $file-content with possible content prepended
     */
    public function comment($comment, $toAssetContent)
    {
        #Noop, comment prepending would break binary file
        return;
    }

}
