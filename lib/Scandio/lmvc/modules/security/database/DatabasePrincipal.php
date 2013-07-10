<?php

namespace Scandio\lmvc\modules\security\database;

use Scandio\lmvc\modules\security;
use Scandio\troba;

class DatabasePrincipal extends security\JsonPrincipal {

    public function __construct($userClass) {
        parent::__construct($userClass);
    }

    public function authenticate($username, $password) {
        $user = UserModel::findBy('username', $user);

        return ($user && $user->getPassword() == sha1($password));
    }

    public function getUser($username) {
        $user = UserModel::findBy('username', $username);

        return $user ? $user : null;
    }

    public function getUsers() {
        $users = UserModel::findAll();

        return $users;
    }

    public function getRole($role) {
        $role = RoleModel::findBy('role', $role);

        return $role ? $role : null;
    }

    public function getRoles() {
        $roles = RoleModel::findAll();

        return $roles;
    }

    public function getGroup($group) {
        $group = GroupModel::findBy('group', $group);

        return $group ? $group : null;
    }

    public function getGroups() {
        $groups = GroupModel::findAll();

        return $groups;
    }

    public function getUserRoles($username) {

    }

    public function getUserGroups($username) {

    }

    public function existsUser($username) {
        $user = UserModel::findBy('username', $user);

        return $user ? true : false;
    }

    public function isUserInRole($username, $role) {

    }

    public function isUserInGroup($username, $group) {

    }
}