<?php

namespace Scandio\lmvc\modules\security\user;

abstract class AbstractUser
{
    /**
     * @param string $username the username
     * @param object $userData any object with getters for additional user data
     */
    public abstract function __construct($username, $userData);

    /**
     * @param string $name
     * @return mixed
     */
    public abstract function __get($name);

    /**
     * @return bool
     */
    public abstract function isAuthenticated();

    /**
     * @param string $role
     * @return bool
     */
    public abstract function isInRole($role);

    /**
     * returns true when the user is in all of the roles
     *
     * @param string $role (pass as many roles as you like)
     * @return bool
     */
    public function isInRoles() {
        $roles = func_get_args();
        $result = true;
        foreach ($roles as $role) {
            $result = $result && $this->isInRole($role);
        }
        return $result;
    }

    /**
     * returns true when the user is in one or more of the roles
     *
     * @param string $role (pass as many roles as you like)
     * @return bool
     */
    public function isInOneRole() {
        $roles = func_get_args();
        foreach ($roles as $role) {
            if ($this->isInRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $group
     * @return bool
     */
    public abstract function isInGroup($group);
}