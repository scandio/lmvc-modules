<?php

namespace Scandio\lmvc\modules\registration\controllers;

use Scandio\lmvc\Controller;
use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\modules\registration\Registration as RegistrationMediator;


class Registration extends Controller
{
    public static function index()
    {
        return static::redirect('Registration::register');
    }

    public static function register()
    {
        return static::render();
    }

    public static function signup() {
        $mediator = RegistrationMediator::get();

        $credentials = [
          'username'            =>  static::request()->username,
          'password'            =>  static::request()->password,
          'passwordRetyped'     =>  static::request()->password_retyped,
          'fullname'            =>  static::request()->fullname,
          'email'               =>  static::request()->email,
          'phone'               =>  static::request()->phone,
          'mobile'              =>  static::request()->mobile
        ];

        $areCredientialsValid = (
            $mediator->isValidPassword($credentials['password'], $credentials['passwordRetyped']) &&
            $mediator->arePossibleCredentials($credentials['username'], $credentials['password'])
        );

        if ($areCredientialsValid) {
            $credentials['password'] = sha1($credentials['password']);

            $mediator->signup($credentials);

            return static::redirect('Registration::success');
        } else {
            return static::redirect('Registration::failure');
        }
    }

    public static function failure()
    {
        static::render();
    }

    public static function success()
    {
        static::render();
    }
}