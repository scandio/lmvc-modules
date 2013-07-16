<?php

/**
 * Class Session
 */
class Session
{
    /**
     * Sets a value $value at $attr via dot-notation.
     *
     * @param $attr in dot-notation to the session's value to be set
     * @param $value value to be set at $attr
     */
    public static function set($attr, $value)
    {

    }

    /**
     * Gets a value at $attr via dot-notation.
     *
     * @param $attr in dot-notation to the session's value to be set
     */
    public static function get($attr)
    {

    }

    /**
     * Recursively replaces all values in session by key and value.
     *
     * @param array $array nested array containing values to be set in session.
     */
    public static function replace($array)
    {

    }

    /**
     * Recursively merges all values in session by key and value.
     *
     * @param array $array nested array containing values to merged into session.
     */
    public static function merge($array)
    {

    }

    /**
     * A little helper resolving dot-notation at an array.
     *
     * @param $dot notation returning the value at the last pointer of the dot-notation.
     * @param array $array to be accessed.
     */
    private static function resolveDotNotation($dot, $array = null)
    {
        $array = $array == null ? $_SESSION : $array;
    }
}