<?php

namespace Scandio\lmvc\modules\registration\controllers;

use Scandio\lmvc;
use Scandio\lmvc\Controller;
use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\modules\registration\Registration as RegistrationMediator;
use Scandio\lmvc\modules\registration\forms;


class Registration extends Controller
{
    public static function index()
    {
        return static::redirect('Registration::register');
    }

    public static function register()
    {
        if (static::request()->signup !== null) {
            $signupForm = new forms\Signup();
            $signupForm->validate(static::request());

            if (!$signupForm->isValid()) {
                return static::render([
                    'errors' => $signupForm->getErrors()
                ]);
            } else {
                static::signup();
            }
        } else {
            static::render();
        }
    }

    public static function signup($redirect = true) {
        $mediator = RegistrationMediator::get();

        $credentials = [
          'username'            =>  static::request()->username,
          'password'            =>  static::request()->password,
          'passwordRetyped'     =>  static::request()->passwordRetyped,
          'fullname'            =>  static::request()->fullname,
          'email'               =>  static::request()->email,
          'phone'               =>  static::request()->phone,
          'mobile'              =>  static::request()->mobile
        ];

        $areCredentialsValid = (
            $mediator->isValidPassword($credentials['password'], $credentials['passwordRetyped']) &&
            $mediator->arePossibleCredentials($credentials['username'], $credentials['password'])
        );

        if ($areCredentialsValid) {
            $credentials['password'] = sha1($credentials['password']);

            $mediator->signup($credentials);

            return $redirect ? static::redirect('Registration::success') : $mediator->getSignedUpUser();
        } else {
            return $redirect ? static::redirect('Registration::failure') : false;
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