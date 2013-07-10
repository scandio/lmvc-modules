<?php

namespace Scandio\lmvc\modules\registration\handlers;

interface MediatorInterface
{
    public function arePossibleCredentials($username, $password);
    public function isValidPassword($password, $passwordRetyped);
    public function signup($credentials);
}