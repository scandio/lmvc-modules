<?php

namespace Scandio\lmvc\modules\security;

use Scandio\lmvc\Controller;

class AnonymousController extends Controller
{
    /**
     * @var UserInterface
     */
    protected static $currentUser;

    /**
     * @var string
     */
    protected static $controllerRole = 'anonymous';

    /**
     * @return bool
     */
    public static function preProcess()
    {
        if (!parent::preProcess()) {
            return false;
        }
        static::$currentUser = Security::get()->currentUser();
        static::setRenderArg('currentUser', static::$currentUser);
        return true;
    }

    /**
     * @return bool
     */
    public static function forbidden()
    {
        return static::redirect('Security::forbidden');
    }
}