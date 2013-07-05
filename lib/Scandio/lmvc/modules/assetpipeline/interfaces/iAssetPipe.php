<?php

namespace Scandio\lmvc\modules\assetpipeline\interfaces;

interface iAssetPipe
{
    public function setCacheDirectory($cacheDirectory);

    public function setAssetDirectory($assetDirectory);

    public function process($asset, $options = []);
}