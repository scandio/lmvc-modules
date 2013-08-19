<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

use CoffeeScript;

/**
 * Class CoffeescriptPipe
 * @package Scandio\lmvc\modules\assetpipeline\assetpipes
 *
 * Handles Css files to be processed by pipe.
 */
class CoffeescriptPipe extends AbstractAssetPipe
{

    protected static
        $_contentType   = "application/javascript";

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
    private function _min($js)
    {
        return \JSMinPlus::minify($js);
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
        $file = $this->_assetDirectory . DIRECTORY_SEPARATOR . $asset;
        $js = null;

        if (!$this->_hasDefaultMimeType($asset)) {
            try
            {
                $coffee = file_get_contents($file);

                // See available options above.
                $js = CoffeeScript\Compiler::compile($coffee, array('filename' => $file));

                if(in_array('min', $options)) {
                    $js = $this->_min($js);
                }

                $js = $this->comment($errors, $js);
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
                $js = $e->getMessage();
            }
        } else {
            $js = file_get_contents($file);
        }

        return $js;
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
        if (strlen($comment) > 0) {
            return "###\n$comment\n###\n\n".$toAssetContent;
        } else {
            return $toAssetContent;
        }
    }

}