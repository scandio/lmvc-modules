<?php

namespace Scandio\lmvc\modules\htmltag;

/**
 * Class Helper
 * @package Scandio\lmvc\modules\htmltag
 *
 * Little helper class responsible for generating tags, paring attributes etc.
 */
class Helper {

    /**
     * Builds an html-tag by tag-name, attributes and content.
     *
     * @param string $tag name to be generated
     * @param array|string $attr ibutes of html-tag
     * @param string|bool $content of html-tag if intended
     *
     * @return string
     */
    public static function tag($tag, $attr = array(), $content = false)
    {
        # Don't pass me null-ed content
        $hasContent = ($content !== false and $content !== null);

        # Open the tag with tag-name
        $html = '<'.$tag;

        # If $attr is not empty transform array in case or just concat string
        $html .= ( !empty($attr) ) ? ' '.(is_array($attr) ? static::attr($attr) : $attr) : '';

        # If content is passed close tag > otherwise without content option '/>'
        $html .= $hasContent ? '>' : ' />';

        # If content pass concat otherwise leave empty
        $html .= $hasContent ? $content.'</'.$tag.'>' : '';

        return $html;
    }

    /**
     * Flattens array of attributes into html-compliant attribute string.
     *
     * @param array $attr ibutes to be parsed into string
     *
     * @return string
     */
    public static function attr($attr)
    {
        $attrStr = '';

        foreach ($attr as $property => $value)
        {
            # Continue on unusable values
            if($value === null or $value === false) { continue; }

            # Numeric props mean something like selected = "selected"
            if (is_numeric($property)) { $property = $value; }

            # Concat prop and value
            $attrStr .= $property.'="'.$value.'" ';
        }

        # Remove potentially last whitespace
        return trim($attrStr);
    }
}