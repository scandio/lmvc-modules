<?php

namespace Scandio\lmvc\modules\registration\handlers;

use Scandio\lmvc\modules\security\handlers\database\models;
use Scandio\troba;


class DatabaseMediator implements MediatorInterface
{
    protected
        $signedUpUser;

    public function arePossibleCredentials($username, $password)
    {
        if ($username == null || $password == null) return false;

        $user = models\Users::findBy('username', $username)->one();

        return ( $user == null );
    }

    public function isValidPassword($password, $passwordRetyped)
    {
        return true;
    }

    public function signup($credentials)
    {
        $user = new models\Users();

        $user->username = $credentials['username'];
        $user->fullname = $credentials['fullname'];
        $user->password = $credentials['password'];
        $user->email = $credentials['email'];
        $user->phone = $credentials['phone'];
        $user->mobile = $credentials['mobile'];

        $this->signedUpUser = $user->insert();
    }

    public function edit($credentials)
    {
        $user = new models\Users();

        $user->id           = $credentials['id'];
        $user->fullname     = $credentials['fullname'];
        $user->password     = $credentials['password'];
        $user->email        = $credentials['email'];
        $user->phone        = $credentials['phone'];
        $user->mobile       = $credentials['mobile'];

        $this->signedUpUser = $user->save();
    }

    public function getSignedUpUser()
    {
        return $this->signedUpUser;
    }

    public function getUserById($id)
    {
        $user = models\Users::findBy('id', $id)->one();

        return $user;
    }
}