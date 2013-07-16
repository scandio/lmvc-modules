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

    public static function img($filename = null)
    {
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];
            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            $uploadIntoPath = static::$config['root'] . DIRECTORY_SEPARATOR. static::$config['uploadDirectory'] . DIRECTORY_SEPARATOR;

            $targetFileName = "";

            if ($filename == "sha1") {$targetFileName = sha1_file($tempFile) . "." . $extension;}
            elseif ($filename != null) {$targetFileName = $filename;}
            else {$targetFileName = $_FILES['file']['name'];}

            $targetFile =  $uploadIntoPath . $targetFileName;

            move_uploaded_file($tempFile, $targetFile);

            self::renderJson(array(
                'path'      => static::$config['uploadDirectory'] . DIRECTORY_SEPARATOR,
                'filename'  => $targetFileName)
            );
        } else {
            self::renderJson(array('error' => 'No files in $_FILES[]!'));
        }
    }
}