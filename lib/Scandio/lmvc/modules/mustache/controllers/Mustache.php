<?php

namespace Scandio\lmvc\modules\mustache\controllers;

use \Scandio\lmvc\Controller;
use \Scandio\lmvc\modules\mustache\Mustache as Mustache_Engine;

class Mustache extends Controller
{
    public static function index($template)
    {
        echo Mustache_Engine::render($template, self::request());
    }
}