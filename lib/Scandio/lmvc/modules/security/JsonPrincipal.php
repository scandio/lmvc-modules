<?php

namespace Scandio\lmvc\modules\security;

use Scandio\lmvc\LVCConfig;

class JsonPrincipal extends AbstractPrincipal
{
    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticate($username, $password)
    {
        $users = LVCConfig::get()->security->users;
        return (isset($users->{$username}) && ($users->{$username}->password == $password));
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] === true));
    }

    /**
     * @return AbstractUser
     */
    public function currentUser()
    {
        return (isset($_SESSION['current_user'])) ?
            new $this->userClass($_SESSION['current_user'], $this->getUser($_SESSION['current_user'])) :
            new $this->userClass('anonymous', new \StdClass());
    }

    /**
     * @param string $username
     * @return AbstractUser
     */
    public function getUser($username)
    {
        return $this->getUsers()[$username];
    }

    /**
     * @return AbstractUser[]
     */
    public function getUsers()
    {
        $result = array();
        foreach (LVCConfig::get()->security->users as $username => $user) {
            $result[$username] = new $this->userClass($username, $user);
        }
        return $result;
    }

    /**
     * @param string $role
     * @return object
     */
    public function getRole($role)
    {
        return $this->getRoles()[$role];
    }

    /**
     * @return object[]
     */
    public function getRoles()
    {
        return LVCConfig::get()->security->roles;
    }

    /**
     * @param string $group
     * @return string[]
     */
    public function getGroup($group)
    {
        return $this->getGroups()[$group];
    }

    /**
     * @return array[]
     */
    public function getGroups()
    {
        return LVCConfig::get()->security->groups;
    }

    /**
     * @param string $username
     * @return string[]
     */
    public function getUserRoles($username)
    {
        $result = array();
        $groups = $this->getUserGroups($username);
        foreach ($this->getRoles() as $role => $members) {
            if (isset($members->users) && in_array($username, $members->users)) {
                $result[] = $role;
                continue; // unnecessary to check groups of this role
            }
            if (isset($members->groups)) {
                foreach ($groups as $group) {
                    if (in_array($group, $members->groups)) {
                        $result[] = $role;
                        break; // unnecessary to check other groups of this role
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param string $username
     * @return string[]
     */
    public function getUserGroups($username)
    {
        $result = array();
        foreach ($this->getGroups() as $group => $members) {
            if (in_array($username, $members)) {
                $result[] = $group;
            }
        }
        return $result;
    }

    /**
     * @param string $username
     * @param string $role
     * @return bool
     */
    public function isUserInRole($username, $role) {
        return in_array($role, $this->getUserRoles($username));
    }

    /**
     * @param string $username
     * @param string $group
     * @return bool
     */
    public function isUserInGroup($username, $group)
    {
        return in_array($group, $this->getUserGroups($username));
    }
}