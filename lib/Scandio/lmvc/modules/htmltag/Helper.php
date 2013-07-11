<?php

namespace Scandio\lmvc\modules\htmltag;

/**
 * Class Helper
 * @package Scandio\lmvc\modules\htmltag
 *
 * Little helper class responsible for generating tags, parsing attributes etc.
 */
class Helper {

    /**
     * Builds an html-tag by tag-name, attributes and content.
     *
     * @param string $tag name to be generated
     * @param array|string $attrs ibutes of html-tag
     * @param string|bool $content of html-tag if intended
     *
     * @return string
     */
    public static function tag($tag, $attrs = [], $content = false)
    {
        # Don't pass me null-ed content
        $hasContent = ($content !== false && $content !== null);

        # Content may also be nested which generates a tag per element
        if ($hasContent && is_array($content)) {
            # Start a new line to secure from previous output
            $html .= PHP_EOL;

            # Enter the looopsiloooo
            foreach ($content as $unrolledContent) {
                # Recursion calling with EOL at the end
                $html .= static::tag($tag, $attrs, $unrolledContent).PHP_EOL;
            }
        } else {
            # Open the tag with tag-name
            $html = '<'.$tag;

            # If $attr is not empty transform array in case or just concat string
            $html .= ( !empty($attrs) ) ? ' '.(is_array($attrs) ? static::attr($attrs) : $attrs) : '';

            # If content is passed close tag > otherwise without content option '/>'
            $html .= $hasContent ? '>' : ' />';

            # If content passed concat and close otherwise leave empty
            $html .= $hasContent ? $content.'</'.$tag.'>' : '';
        }

        return $html;
    }

    /**
     * Flattens array of attributes into html-compliant attribute string.
     *
     * @param array $attrs ibutes to be parsed into string
     *
     * @return string
     */
    public static function attr($attrs)
    {
        $attrStr = '';

        foreach ($attrs as $property => $value) {
            # Continue on unusable values
            if($value === null || $value === false) { continue; }

            # Numeric props mean something like selected = "selected"
            if (is_numeric($property)) { $property = $value; }

            # Concat prop and value
            $attrStr .= $property .'="'.$value.'" ';
        }

        # Remove potentially last whitespace
        return trim($attrStr);
    }
}