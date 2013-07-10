<?php

namespace Scandio\lmvc\modules\security\handlers\database\models;

use troba\Model\Finders;

/**
 * Class UserModel
 * @package Scandio\lmvc\modules\security\database
 *
 * Model used by EQM representing a user.
 */
class UserModel {
    use Finders, Getters;

    protected $__table = "Users";

    public function getGroups() {
        $groups = static::query()
                    ->leftJoin(new \StdClass, 'Users.id = User_to_Roles.user_id')
                    ->leftJoin(new RoleModel(), 'User_to_Roles.role_id = Roles.id')->all();

        return $groups;
    }

    public function getRoles() {
        $roles = static::query()
            ->leftJoin(new \StdClass, 'Users.id = User_to_Groups.user_id')
            ->leftJoin(new RoleModel(), 'User_to_Groups.group_id = Groups.id')->all();

        return $roles;
    }
}