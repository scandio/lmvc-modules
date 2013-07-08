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
        $file = $this->_assetDirectory . DIRECTORY_SEPARATOR . $asset;
        $js = null;

        try
        {
            $coffee = file_get_contents($file);

            // See available options above.
            $js = CoffeeScript\Compiler::compile($coffee, array('filename' => $file));

            if(in_array('min', $options)) {
                $js = $this->_min($file);
            }
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }

        return $js;
    }

}