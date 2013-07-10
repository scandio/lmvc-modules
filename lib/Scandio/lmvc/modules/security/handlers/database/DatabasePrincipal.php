<?php

namespace Scandio\lmvc\modules\security\handlers\database;

use Scandio\lmvc\modules\security\handlers;
use Scandio\lmvc\modules\security\handlers\database\models;
use Scandio\troba;

class DatabasePrincipal extends handlers\AbstractSessionPrincipal
{

    public function __construct($userClass)
    {
        parent::__construct($userClass);
    }

    public function authenticate($username, $password)
    {
        $user = models\Users::findBy('username', $username)->one();

        return ($user && $user->password == sha1($password));
    }

    public function getUser($username)
    {
        $user = models\Users::findBy('username', $username)->one();

        return $user ? $user : null;
    }

    public function getUsers()
    {
        $users = models\Users::findAll();

        return $users;
    }

    public function getRole($role)
    {
        $role = models\Roles::findBy('role', $role)->one();

        return $role ? $role : null;
    }

    public function getRoles()
    {
        $roles = models\Roles::findAll();

        return $roles;
    }

    public function getGroup($group)
    {
        $group = models\Groups::findBy('group', $group)->one();

        return $group ? $group : null;
    }

    public function getGroups()
    {
        $groups = models\Groups::findAll();

        return $groups;
    }

    public function getUserRoles($username)
    {
        $roles = models\Roles::getByUsername($username);

        return $roles;
    }

    public function getUserGroups($username)
    {
        $groups = models\Groups::getByUsername($username);

        return $groups;
    }

    public function existsUser($username)
    {
        $user = Users::findBy('username', $user)->one();

        return $user ? true : false;
    }

    public function isUserInRole($username, $role)
    {
        $roles = models\Roles::getByUsername($username);

        foreach ($roles as $role) {
            if ($role->id == $role->id)
                return true;
        }

        return false;
    }

    public function isUserInGroup($username, $group)
    {
        $groups = models\Groups::getByUsername($username);

        foreach ($groups as $group) {
            if ($group->id == $group->id)
                return true;
        }

        return false;
    }
}