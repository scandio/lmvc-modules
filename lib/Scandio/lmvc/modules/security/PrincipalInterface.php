<?php

namespace Scandio\lmvc\modules\security;

interface PrincipalInterface
{
    /**
     * @param string $userClass
     */
    public function __construct($userClass);

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticate($username, $password);

    /**
     * @return bool
     */
    public function isAuthenticated();

    /**
     * @return AbstractUser
     */
    public function currentUser();

    /**
     * @param string $username
     * @return AbstractUser
     */
    public function getUser($username);

    /**
     * @return AbstractUser[]
     */
    public function getUsers();

    /**
     * @param string $role
     * @return object
     */
    public function getRole($role);

    /**
     * @return object[]
     */
    public function getRoles();

    /**
     * @param string $group
     * @return string[]
     */
    public function getGroup($group);

    /**
     * @return array[]
     */
    public function getGroups();

    /**
     * @param string $username
     * @return string[]
     */
    public function getUserRoles($username);

    /**
     * @param string $username
     * @return string[]
     */
    public function getUserGroups($username);

    /**
     * @param string $username
     * @return bool
     */
    public function existsUser($username);

    /**
     * @param string $username
     * @param string $role
     * @return bool
     */
    public function isUserInRole($username, $role);

    /**
     * @param string $username
     * @param string $group
     * @return bool
     */
    public function isUserInGroup($username, $group);
}