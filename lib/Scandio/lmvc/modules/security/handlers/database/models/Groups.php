<?php

namespace Scandio\lmvc\modules\security\handlers\database\models;

use troba\Model;

/**
 * Class GroupModel
 * @package Scandio\lmvc\modules\security\database
 *
 * Model used by EQM representing a group.
 */
class Groups
{
    use Model\Getters, Model\Finders;

    public static function getByUsername($username)
    {
        $groups = static::query()
            ->leftJoin('User_to_Groups', 'Groups.id = User_to_Groups.group_id')
            ->leftJoin(new Users(), 'User_to_Groups.user_id = Users.id')
            ->where('Users.username = :username', ['username' => $username])
            ->all();

        return $groups;
    }
}