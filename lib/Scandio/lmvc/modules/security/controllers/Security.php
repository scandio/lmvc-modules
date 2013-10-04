<?php

namespace Scandio\lmvc\modules\security\controllers;

use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\LVC;
use Scandio\lmvc\modules\session\Session;
use Scandio\lmvc\modules\snippets\Snippets;
use Scandio\lmvc\modules\security\AnonymousController;
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
        $auth = static::authenticate();
        static::redirect($auth['controllerAction'], $auth['params']);
        return $auth['success'];
    }

    /**
     * @return bool
     */
    public static function ajaxLogin()
    {
        return static::renderHtml(Snippets::login('ajaxLogin'));
    }

    public static function postAjaxLogin() {
        $auth = static::authenticate();
        return static::renderJson($auth);
    }

    /**
     * @return array
     */
    protected static function authenticate()
    {
        $principal = SecurityPrincipal::get();
        if ($principal->authenticate(static::request()->username, static::request()->password)) {
            Session::set('security.current_user', static::request()->username);
            Session::set('security.authenticated', true);

            $uri = Session::get('security.called_before_login');
            Session::set('security.called_before_login', null);

            return [
                'success' => true,
                'controllerAction' => $uri
            ];
        } else {
            return [
                'success' => false,
                'controllerAction' => 'Security::login',
                'params' => ['failure']];
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