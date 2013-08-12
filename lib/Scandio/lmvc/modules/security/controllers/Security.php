<?php

namespace Scandio\lmvc\modules\security\controllers;

use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\LVC;
use Scandio\lmvc\modules\security\AnonymousController;
use Scandio\lmvc\modules\session\Session;
use Scandio\lmvc\modules\security\Security as SecurityPrincipal;

class Security extends AnonymousController
{
    /**
     * @return bool
     */
    public static function index()
    {
        return static::redirect('Security::login');
    }

    /**
     * @return bool
     */
    public static function login($failure = null)
    {
        return static::render([
            'failure'   =>  $failure == null ? false : true
        ]);
    }

    /**
     * @return bool
     */
    public static function postLogin()
    {
        $principal = SecurityPrincipal::get();
        if ($principal->authenticate(static::request()->username, static::request()->password)) {
            Session::set('security', array(
                'current_user'  => static::request()->username,
                'authenticated' => true
            ));

            $controllerAction = Session::get('security.called_before_login.controller') .
                '::' . Session::get('security.called_before_login.action');

            $params = Session::get('security.called_before_login.params');

            Session::set('security.called_before_login', null);

            return static::redirect($controllerAction, $params);
        } else {
            return static::redirect('Security::login', 'failure');
        }
    }

    /**
     * @return bool
     */
    public static function logout()
    {
        Session::stop();

        $logoutAction = (isset(LVCConfig::get()->security->logoutAction)) ? LVCConfig::get()->security->logoutAction : 'Application::index';
        return static::redirect($logoutAction);
    }

    /**
     * @return bool
     */
    public static function forbidden()
    {
        header('HTTP/1.0 403 Forbidden');
        return static::render();
    }
}