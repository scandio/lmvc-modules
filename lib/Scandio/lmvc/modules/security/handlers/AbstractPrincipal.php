<?php

namespace Scandio\lmvc\modules\security\handlers;

use Scandio\lmvc\modules\security\Bootstrap;

abstract class AbstractPrincipal implements PrincipalInterface
{
    /**
     * @var string
     */
    protected $userClass;

    /**
     * @param string $userClass
     */
    public function __construct($userClass)
    {
        if ($userClass && is_subclass_of($userClass, '\\Scandio\\lmvc\\modules\\security\\user\\AbstractUser')) {
            $this->userClass = $userClass;
        } else {
            $this->userClass = Bootstrap::getNamespace() . '\\user\\User';
        }
    }

    /**
     * @param string $username
     * @return bool
     */
    public function existsUser($username) {
        return is_object($this->getUser($username));
    }

    /**
     * @return string[]
     */
    public function getCurrentUserGroups() {
        return $this->getUserGroups($this->currentUser()->username);
    }

    /**
     * @return string[]
     */
    public function getCurrentUserRoles() {
        return $this->getUserRoles($this->currentUser()->username);
    }
}