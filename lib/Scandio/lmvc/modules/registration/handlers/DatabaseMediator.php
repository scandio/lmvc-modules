<?php

namespace Scandio\lmvc\modules\registration\handlers;

use Scandio\lmvc\modules\security\handlers\database\models;
use Scandio\troba;

class DatabaseMediator implements MediatorInterface
{

    public function arePossibleCredentials($username, $password)
    {
        if ($username == null || $password == null) return false;

        $user = models\Users::findBy('username', $username)->one();

        return ( $user == null );
    }

    public function isValidPassword($password, $passwordRetyped)
    {
        return ( sha1($password) == sha1($passwordRetyped) );
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

        $user->insert();
    }
}