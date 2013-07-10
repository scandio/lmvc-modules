<?php

namespace Scandio\lmvc\modules\security\handlers\database\models;

use troba\Model\Finders;
use troba\Model\Getters;

/**
 * Class RoleModel
 * @package Scandio\lmvc\modules\security\database
 *
 * Model used by EQM representing a role.
 */
class RoleModel {
    use Finders, Getters;

    protected $__table = "Roles";
}