<?php

namespace Scandio\lmvc\modules\mustache;

class Mustache
{
    /**
     * @param string $templateString
     * @param array $data
     * @return string
     */
    public static function renderString($templateString, $data = array())
    {
        $mustache = new \Mustache_Engine();
        return $mustache->render($templateString, $data);
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public static function render($template, $data = array())
    {
        return self::renderString(self::getTemplate($template), $data);
    }

    /**
     * @param string $template
     * @return string
     */
    public static function get($template)
    {
        return self::getTemplate($template);
    }

    /**
     * @param string $template
     * @return string
     */
    private static function getTemplate($template)
    {
        return file_get_contents('./templates/' . $template . '.mustache');
    }
}