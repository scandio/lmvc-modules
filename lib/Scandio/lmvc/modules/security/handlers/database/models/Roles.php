<?php

namespace Scandio\lmvc\modules\security\handlers\database\models;

use troba\Model;

/**
 * Class RoleModel
 * @package Scandio\lmvc\modules\security\database
 *
 * Model used by EQM representing a role.
 */
class Roles
{
    use Model\Getters, Model\Finders;

    public static function getByUsername($username)
    {
        $roles = static::query()
            ->leftJoin('User_to_Roles', 'Roles.id = User_to_Roles.role_id')
            ->leftJoin(new Users(), 'User_to_Roles.user_id = Users.id')
            ->where('Users.username = :username', ['username' => $username])
            ->all();

        return $roles;
    }
}