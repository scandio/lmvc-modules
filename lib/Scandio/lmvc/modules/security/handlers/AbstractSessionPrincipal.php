<?php

namespace Scandio\lmvc\modules\security\handlers;

use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\modules\session\Session;

/**
 * Class SessionPrincipal
 * @package Scandio\lmvc\modules\security\handlers
 *
 * Abstracts interaction which is generally bound to the $_SESSION and holds for every extending
 * principal in the object-graph.
 *      NOTE:
 *          Stuff which was previously mostly in the JsonPrincipal which lead to everybody extending that
 *          class. This in a way is not very meaningful in object-oriented terms.
 */
abstract class AbstractSessionPrincipal extends AbstractPrincipal
{

    public function __contruct($userClass = null)
    {
        parent::__construct($userClass);
    }

    public function isAuthenticated()
    {
        return ( Session::get('security.authenticated') === true );
    }

    public function currentUser()
    {
        return ( Session::get('security.current_user') ) ?
            new $this->userClass(Session::get('security.current_user'), $this->getUser(Session::get('security.current_user'))) :
            new $this->userClass('anonymous', new \StdClass());
    }

    public function isUserInRole($username, $role)
    {
        return in_array($role, $this->getUserRoles($username));
    }

    public function isUserInGroup($username, $group)
    {
        return in_array($group, $this->getUserGroups($username));
    }

    public function getUser($username)
    {
        return $this->getUsers()[$username];
    }

    public function getRole($role)
    {
        return $this->getRoles()[$role];
    }

    public function getRoles()
    {
        return LVCConfig::get()->security->roles;
    }

    public function getGroup($group)
    {
        return $this->getGroups()[$group];
    }

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
}