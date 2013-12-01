<?php

namespace Scandio\lmvc\modules\security;

use Scandio\lmvc\Config;

class Security
{
    /**
     * @var null|PrincipalInterface
     */
    protected static $principal = null;

    /**
     * @return PrincipalInterface
     * @throws \Exception
     */
    public static function get()
    {
        if (is_null(static::$principal)) {
            $class = Config::get()->security->principal;
            $userClass = null;
            if (isset(Config::get()->security->principalUser)) {
                $userClass = Config::get()->security->principalUser;
            }
            if (class_exists($class) && is_subclass_of($class, '\\Scandio\\lmvc\\modules\\security\\handlers\\PrincipalInterface')) {
                static::$principal = new $class($userClass);
            } else {
                throw new \Exception('no valid Authenticator class configured in config.json');
            }
        }
        return static::$principal;
    }
}