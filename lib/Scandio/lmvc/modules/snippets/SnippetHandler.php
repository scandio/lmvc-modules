<?php

namespace Scandio\lmvc\modules\snippets;

use \Scandio\lmvc\LVC;

abstract class SnippetHandler
{

    protected static $snippetFile;
    protected static $prefix = '';
    protected static $snippetPath = array();

    /**
     * used to include the snippets - behaviour may be overridden by private or public static functions
     *
     * @static
     * @param string $name camelCasedName of the snippet
     * @param array $params parameters passed to the snippet
     * @return mixed|string the result of your private static function, an ErrorMessage or just an empty string
     */
    public static function __callStatic($name, $params)
    {
        $result = "";
        if (method_exists(get_called_class(), $name)) {
            $result = call_user_func_array('static::' . $name, $params);
        } else {
            self::$snippetFile = self::searchSnippet(static::$prefix . LVC::camelCaseTo($name) . '.html');
            if (self::$snippetFile) {
                $app = LVC::get(); // should be available in the snippet's scope as in views for convenience
                include(self::$snippetFile);
            } elseif (preg_match('/^get[A-Z]/', $name)) {
                ob_start();
                echo __callStatic(lcfirst(substr($name, 3)));
                $result = ob_get_contents();
                ob_clean();
            } else {
                $result = PHP_EOL . "<!-- No snippet file for " . get_called_class() . "::" . $name . "() exists. -->" . PHP_EOL;
            }
        }
        return $result;
    }

    /**
     * registers a new snippet directory to search for the snippets
     *
     * @static
     * @param array|string $path specifies the directory to register
     */
    public static function registerSnippetDirectory($path)
    {
        if (is_array($path)) {
            $snippetPath = implode(DIRECTORY_SEPARATOR, $path);
        } elseif (is_string($path)) {
            $snippetPath = $path;
        } else {
            echo PHP_EOL . "<!-- Couldn't register SnippetDirectory:" . PHP_EOL;
            var_dump($path);
            echo "-->" . PHP_EOL;
            return;
        }

        $class = get_called_class();
        if (isset(self::$snippetPath[$class])) {
            array_unshift(self::$snippetPath[$class], $snippetPath);
        } else {
            self::$snippetPath[$class] = array($snippetPath);
        }
    }

    /**
     * searches for the snippet in the registered directories
     *
     * @static
     * @param string $snippet the snippet to search for
     * @return string|bool either the snippet's full path or false
     */
    private static function searchSnippet($snippet)
    {
        $class = get_called_class();
        if (!isset(self::$snippetPath[$class])) {
            return false;
        }
        foreach (self::$snippetPath[$class] as $path) {
            $snippetPath = LVC::get()->config->appPath . $path . DIRECTORY_SEPARATOR . $snippet;
            if (file_exists($snippetPath)) {
                return $snippetPath;
            }
        }
        return false;
    }

}
