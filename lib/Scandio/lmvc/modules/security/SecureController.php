<?php

namespace Scandio\lmvc\modules\security;

use Scandio\lmvc\modules\session\Session;
use Scandio\lmvc\LVC;

class SecureController extends AnonymousController
{
    /**
     * @return bool
     */
    public static function preProcess()
    {
        if (!parent::preProcess()) {
            return false;
        }
        if (!static::$currentUser->isAuthenticated()) {
            Session::set('security.called_before_login', $_SERVER['REQUEST_URI']);

            static::redirect('Security::login');
            return false;
        } else {
            if (static::$controllerRole == 'anonymous' || static::$currentUser->isInRole(static::$controllerRole)) {
                return true;
            } else {
                return static::forbidden();
            }
        }
    }
}