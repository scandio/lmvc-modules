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
        return true;
    }

    public static function render($renderArgs = array(), $template = null, $masterTemplate = null)
    {
        static::setRenderArg('currentUser', static::$currentUser);
        return parent::render($renderArgs, $template, $masterTemplate);
    }

    /**
     * @return bool
     */
    public static function forbidden()
    {
        return static::redirect('Security::forbidden');
    }
}