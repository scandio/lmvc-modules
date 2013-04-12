<?php

namespace Scandio\lmvc\modules\security\tests;

use Scandio\lmvc\LVCConfig;
use Scandio\lmvc\modules\security\LdapPrincipal;

class LdapPrincipalTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var LdapPrincipal principal
     */
    protected $principal;

    public static function setUpBeforeClass() {
        LVCConfig::initialize('configLdapPrincipalTest.json');
    }

    public function setUp() {
        $this->principal = new LdapPrincipal();
    }

    public function testConnection() {
        $this->assertInstanceOf('Scandio\\lmvc\\modules\\security\\LdapPrincipal', $this->principal);
    }

    /**
     * @depends testConnection
     */
    public function testAuthenticate() {
        $test = LVCConfig::get()->securityTest;
        $this->assertEquals(false, $this->principal->authenticate($test->inexistent_user, $test->inexistent_user_pass));
        $this->assertEquals(true, $this->principal->authenticate($test->existent_user, $test->existent_user_pass));
    }

    /**
     * @depends testConnection
     */
    public function testGetUsers() {
        $users = $this->principal->getUsers();
        $this->assertInternalType('array', $users);
        $this->assertNotEmpty($users);
        return $users;
    }

    /**
     * @depends testGetUsers
     */
    public function testGetUser($users) {
        $username = LVCConfig::get()->securityTest->existent_user;
        $user = $users[$username];
        $this->assertEquals($user, $this->principal->getUser($username));
        $this->assertInstanceOf('\\Scandio\\lmvc\\modules\\security\\UserInterface', $user);
        $this->assertNotEmpty($user->dn);
        $this->assertNotEmpty($user->fullname);
        $this->assertNotEmpty($user->email);
    }

    /**
     * @depends testConnection
     */
    public function testGetGroups() {
        $groups = $this->principal->getGroups();
        $this->assertInternalType('array', $groups);
        $this->assertNotEmpty($groups);
        return $groups;
    }

    /**
     * @depends testGetGroups
     */
    public function testGetGroup($groups) {
        $groupname = array_keys($groups)[0];
        $group = $groups[$groupname];
        $this->assertEquals($group, $this->principal->getGroup($groupname));
        $this->assertInternalType('array', $group);
    }

    /**
     * @depends testGetGroups
     */
    public function testGetUserGroups() {
        $test = LVCConfig::get()->securityTest;
        $userGroups = $this->principal->getUserGroups('anonymous');
        $this->assertInternalType('array', $userGroups);
        $userGroups = $this->principal->getUserGroups($test->inexistent_user);
        $this->assertInternalType('array', $userGroups);
        $userGroups = $this->principal->getUserGroups($test->existent_user);
        $this->assertInternalType('array', $userGroups);
        $this->assertNotEmpty($userGroups);
    }
}
