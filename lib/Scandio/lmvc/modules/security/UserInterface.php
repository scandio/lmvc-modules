<?php

namespace Scandio\lmvc\modules\security;

interface UserInterface
{
    /**
     * @param string $username the username
     * @param object $userData any object with getters for additional user data
     */
    public function __construct($username, $userData);

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name);

    /**
     * @return bool
     */
    public function isAuthenticated();

    /**
     * @param string $role
     * @return bool
     */
    public function isInRole($role);

    /**
     * @param string $group
     * @return bool
     */
    public function isInGroup($group);
}