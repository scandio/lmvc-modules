<?php

namespace Scandio\lmvc\modules\security\handlers\database\models;

use troba\Model;

/**
 * Class UserModel
 * @package Scandio\lmvc\modules\security\database
 *
 * Model used by EQM representing a user.
 */
class Users
{
    use Model\Getters, Model\Finders, Model\Persisters;
}