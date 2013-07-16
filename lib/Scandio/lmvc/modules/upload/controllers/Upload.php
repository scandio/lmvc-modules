<?php

namespace Scandio\lmvc\modules\upload\controllers;

use Scandio\lmvc\LVC;
use Scandio\lmvc\Controller;

class Upload extends Controller
{
    protected static
        $config = [],
        $defaults = [
            'root'              => '',
            'uploadDirectory'   => '/uploads'
        ];

    public static function configure($config = [])
    {
        static::$config = array_replace_recursive(static::$defaults, $config);
    }

    public static function img(/* reading from stream */)
    {
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];

            $uploadIntoPath = static::$config['root'] . DIRECTORY_SEPARATOR. static::$config['uploadDirectory'] . DIRECTORY_SEPARATOR;

            $targetFile =  $uploadIntoPath . $_FILES['file']['name'];

            move_uploaded_file($tempFile, $targetFile);
        }
    }
}