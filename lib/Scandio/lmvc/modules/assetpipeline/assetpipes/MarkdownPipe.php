<?php

namespace Scandio\lmvc\modules\assetpipeline\assetpipes;

/**
 * Class CssPipe
 * @package Scandio\lmvc\modules\assetpipeline\assetpipes
 *
 * Handles font files processed by pipe.
 */
class MarkdownPipe extends AbstractAssetPipe
{

    protected static
        $_contentType   = "text/x-markdown";

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
        $html = null;
        $file = $this->_assetDirectory . DIRECTORY_SEPARATOR . $asset;

        $html = \MarkdownExtended\MarkdownExtended::create()
           ->get('Parser', array())
           ->parse( new \MarkdownExtended\Content(null, $file) )
           ->getContent();

        return $html->getBody();
    }

}