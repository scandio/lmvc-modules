<?php

namespace Scandio\lmvc\modules\registration\controllers;

use Scandio\lmvc;
use Scandio\lmvc\Controller;
use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\modules\registration\Registration as RegistrationMediator;
use Scandio\lmvc\modules\registration\forms;
use Scandio\lmvc\modules\security;


class Registration extends Controller
{
    public static function index()
    {
        return static::redirect('Registration::register');
    }

    public static function signup()
    {
        return static::render();
    }

    public static function postSignup($redirect = true)
    {
        $signupForm = new forms\Signup();
        $signupForm->validate(static::request());

        if (!$signupForm->isValid()) {
            return static::render([
                'errors' => $signupForm->getErrors()
            ]);
        } else {
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
    }

    public static function edit($redirect = true)
    {
        $mediator = RegistrationMediator::get();
        $isPost = static::request()->edit != null;

        if ($isPost == false && security\Security::get()->isAuthenticated()) {
            $redirect ? static::render([
                'user' => $mediator->getUserById(security\Security::get()->currentUser()->id)
            ]) : null;

            return false;
        } else {
            $credentials = [
                'id'                  =>  security\Security::get()->currentUser()->id,
                'password'            =>  static::request()->password,
                'passwordRetyped'     =>  static::request()->passwordRetyped,
                'fullname'            =>  static::request()->fullname,
                'email'               =>  static::request()->email,
                'phone'               =>  static::request()->phone,
                'mobile'              =>  static::request()->mobile
            ];

            $areCredentialsValid = (
                $mediator->isValidPassword($credentials['password'], $credentials['passwordRetyped']) &&
                security\Security::get()->isAuthenticated() && ( security\Security::get()->currentUser()->id != null )
            );

            if ($areCredentialsValid) {
                $credentials['password'] = sha1($credentials['password']);

                $mediator->edit($credentials);

                return $redirect ? static::redirect('Registration::success') : $mediator->getSignedUpUser();
            }
        }

        return $redirect ? static::redirect('Registration::failure') : false;
    }

    public static function postEdit($redirect = true)
    {
        $signupForm = new forms\Signup();
        $signupForm->validate(static::request());

        # Otherwise controller would need to be extended (single actions cant be protected)
        if (!security\Security::get()->isAuthenticated()) {
            return security\controllers\Security::forbidden();
        }
        elseif (!$signupForm->isValid()) {
            return static::render([
                'errors' => $signupForm->getErrors()
            ]);
        } else {
            $mediator = RegistrationMediator::get();

            $credentials = [
                'id'                  =>  security\Security::get()->currentUser()->id,
                'password'            =>  static::request()->password,
                'passwordRetyped'     =>  static::request()->passwordRetyped,
                'fullname'            =>  static::request()->fullname,
                'email'               =>  static::request()->email,
                'phone'               =>  static::request()->phone,
                'mobile'              =>  static::request()->mobile
            ];

            # This is mediator dependent and cant therefore be abstracted into a form
            $areCredentialsValid = (
                $mediator->isValidPassword($credentials['password'], $credentials['passwordRetyped'])
            );

            if ($areCredentialsValid) {
                # Now we can sha1 the password
                $credentials['password'] = sha1($credentials['password']);

                # Save it
                $mediator->edit($credentials);

                # and redirect or save, dependent on input var
                return $redirect ? static::redirect('Registration::success') : $mediator->getSignedUpUser();
            }
        }
    }

    public static function failure()
    {
        return static::render();
    }

    public static function success()
    {
        return static::render();
    }
}