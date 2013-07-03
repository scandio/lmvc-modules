<?php

namespace Scandio\lmvc\modules\sass\controllers;

use Scandio\lmvc\LVC;
use Scandio\lmvc\Controller;

class Sass extends Controller
{
    /**
     * Controller for a cached SASS compiled style sheet
     * The cached compiled file will be saved in app root
     */
    public static function index()
    {
        $cacheFile = implode('-', LVC::get()->params);
        $file = LVC::get()->config->appPath . implode('/', LVC::get()->params);
        if (file_exists($file)) {
            if (file_exists($cacheFile) && filemtime($file) <= filemtime($cacheFile)) {
                $cssStream = "/* cached result from sass compiler */\n" . file_get_contents($cacheFile);
            } else {
                $sassCompiler = new \scssc();
                $cssStream = $sassCompiler->compile(file_get_contents($file));
                file_put_contents($cacheFile, $cssStream);
                $cssStream = "/* compiled result from sass compiler */\n" . $cssStream;
            }
        } else {
            $cssStream = "/* couldn't find the requested file */\n";
        }
        header('Content-Type: text/css');
        echo $cssStream;
    }
}