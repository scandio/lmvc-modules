<?php

namespace Scandio\lmvc\modules\security;

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
            $_SESSION['called_before_login']['controller'] = LVC::get()->controller;
            $_SESSION['called_before_login']['action'] = LVC::get()->actionName;
            $_SESSION['called_before_login']['params'] = LVC::get()->params;
            static::redirect('Security::login');
            return false;
        } else {
            if (static::$controllerRole == 'anonymous' || static::$currentUser->isInRole(static::$controllerRole)) {
                return true;
            } else {
                static::redirect('Security::forbidden');
                return false;
            }
        }
    }
}