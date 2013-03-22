<?php

namespace Scandio\lmvc\modules\less\controllers;

use \Scandio\lmvc\LVC;
use \Scandio\lmvc\Controller;

class Less extends Controller {

    public static function compile() {
        $cacheFile = implode('-', LVC::get()->params);
        $file = LVC::get()->config->appPath . implode('/', LVC::get()->params);
        if (file_exists($file)) {
            if (file_exists($cacheFile) && filemtime($file) <= filemtime($cacheFile)) {
                $cssStream = "/* cached result from less compiler */\n" . file_get_contents($cacheFile);
            } else {
                $lessCompiler = new \lessc($file);
                $cssStream = $lessCompiler->parse();
                file_put_contents($cacheFile, $cssStream);
                $cssStream = "/* compiled result from less compiler */\n" . $cssStream;
            }
        } else {
            $cssStream = "/* couldn't find the requested file */\n";
        }
        header('Content-Type: text/css');
        echo $cssStream;
    }

}