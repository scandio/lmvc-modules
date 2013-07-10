<?php

namespace Scandio\lmvc\modules\security\handlers;

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
        return (isset($_SESSION['security']['authenticated']) && ($_SESSION['security']['authenticated'] === true));
    }

    public function currentUser()
    {
        return (isset($_SESSION['security']['current_user'])) ?
            new $this->userClass($_SESSION['security']['current_user'], $this->getUser($_SESSION['security']['current_user'])) :
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
}