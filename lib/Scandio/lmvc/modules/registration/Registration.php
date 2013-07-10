<?php

namespace Scandio\lmvc\modules\registration;

use Scandio\lmvc\LVCConfig;

class Registration
{
    protected static
        $mediator = null;

    public static function get()
    {
        if (is_null(static::$mediator)) {
            $class = LVCConfig::get()->registration->mediator;

            if (class_exists($class) && is_subclass_of($class, '\\Scandio\\lmvc\\modules\\registration\\handlers\\MediatorInterface')) {
                static::$mediator = new $class();
            } else {
                throw new \Exception('No valid registration mediator class configured in config.json');
            }
        }
        return static::$mediator;
    }
}