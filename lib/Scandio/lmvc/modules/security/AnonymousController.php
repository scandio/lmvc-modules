<?php

namespace Scandio\lmvc\modules\security;

use Scandio\lmvc\Controller;
use Scandio\lmvc\Config;

class AnonymousController extends Controller
{
    /**
     * @var AbstractUser
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
        return true;
    }

    public static function render($renderArgs = array(), $httpCode = 200, $template = null, $masterTemplate = null)
    {
        static::setRenderArg('currentUser', static::$currentUser);
        return parent::render($renderArgs, $template, $httpCode, $masterTemplate);
    }

    /**
     * @return bool
     */
    public static function forbidden()
    {
        $forbiddenAction = (isset(Config::get()->security->forbiddenAction)) ? Config::get()->security->forbiddenAction : 'Security::forbidden';
        static::redirect($forbiddenAction);
        return false;
    }
}