<?php

namespace Scandio\lmvc\modules\security\user;

use Scandio\lmvc\modules\security\Security;

class User extends AbstractUser
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var object
     */
    protected $userData;

    /**
     * @param string $username
     * @param object $userData
     */
    public function __construct($username, $userData)
    {

        $this->username = $username;
        $this->userData = $userData;
    }

    /**
     * @param string $name
     * @return mixed|string
     * @throws \Exception
     */
    public function __get($name)
    {
        if ($name == 'username') {
            return $this->username;
        } else {
            if (property_exists($this->userData, $name)) {
                return $this->userData->{$name};
            } else {
                throw new \Exception("Property {$name} does not exists!");
            }
        }
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return Security::get()->isAuthenticated();
    }

    /**
     * @param string $role
     * @return bool
     */
    public function isInRole($role)
    {
        return Security::get()->isUserInRole($this->username, $role);
    }

    /**
     * @param string $group
     * @return bool
     */
    public function isInGroup($group)
    {
        return Security::get()->isUserInGroup($this->username, $group);
    }
}