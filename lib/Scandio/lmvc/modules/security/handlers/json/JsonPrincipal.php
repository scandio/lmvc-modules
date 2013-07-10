<?php

namespace Scandio\lmvc\modules\security\handlers\json;

use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\modules\security\handlers;

class JsonPrincipal extends handlers\AbstractSessionPrincipal
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
        $roles = $this->getRoles();
        foreach ($roles as $role => $members) {
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
        $roleAdded = true;
        while ($roleAdded) {
            $roleAdded = false;
            foreach ($roles as $role => $members) {
                if (!in_array($role, $result) && isset($members->roles)) {
                    foreach ($members->roles as $memberRole) {
                        if (in_array($memberRole, $result)) {
                            $result[] = $role;
                            $roleAdded = true;
                            break; // unnecessary to check other memberroles of this role
                        }
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
}