<?php

namespace Scandio\lmvc\modules\security\database;

use troba\Model\Finders;
use troba\Model\Getters;

/**
 * Class GroupModel
 * @package Scandio\lmvc\modules\security\database
 *
 * Model used by EQM representing a group.
 */
class GroupModel {
    use Finders, Getters;

    protected $__table = "Groups";
}