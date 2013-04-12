<?php

namespace Scandio\lmvc\modules\security;

use Scandio\lmvc\LVC;

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
            $class = LVC::get()->config->security->principal;
            $userClass = null;
            if (isset(LVC::get()->config->security->principalUser)) {
                $userClass = LVC::get()->config->security->principalUser;
            }
            if (class_exists($class) && is_subclass_of($class, '\\Scandio\\lmvc\\modules\\security\\PrincipalInterface')) {
                static::$principal = new $class($userClass);
            } else {
                throw new \Exception('no valid Authenticator class configured in config.json');
            }
        }
        return static::$principal;
    }
}