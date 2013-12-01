<?php

namespace Scandio\lmvc\modules\security\handlers\ldap;

use Scandio\lmvc\Config;
use Scandio\lmvc\modules\security\handlers;
use Scandio\lmvc\modules\session\Session;

class LdapPrincipal extends handlers\AbstractSessionPrincipal
{
    protected $conn;
    protected $bind;

    public function __construct($userClass = null)
    {
        parent::__construct($userClass);
        $security = Config::get()->security;
        $this->conn = ldap_connect($security->host, $security->port) or die ("The LDAP server couldn't be reached!");
        $this->bind = ldap_bind($this->conn) or die ("Couldn't initialize the connection to the LDAP server!");
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticate($username, $password)
    {
        $security = Config::get()->security;
        try {
            $info = $this->search($username);
            $dn = $info[0]["userprincipalname"][0];

            $conn = ldap_connect($security->host, $security->port);
            return !is_null($dn) && ldap_bind($conn, $dn, $password);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return AbstractUser[]
     */
    public function getUsers()
    {
        if (!Session::get('security.ldap_users')) {
            $security = Config::get()->security;
            $list = ldap_search($this->conn, $security->user_base_dn, '(&(objectclass=user)(memberof=CN=DB-User,CN=Users,DC=scandio,DC=de))');
            $entries = ldap_get_entries($this->conn, $list);
            unset($entries['count']);

            $ldapUsers = array();
            foreach ($entries as $entry) {
                $userId = $entry[$security->username_attribute][0];
                $userMail = "not available";
                if (isset($entry['mail'])) {
                    $userMail = $entry["mail"][0];
                }
                $user = (object)array(
                    'dn' => $entry["distinguishedname"][0],
                    'fullname' => $entry["displayname"][0],
                    'email' => $userMail
                );
                $ldapUsers[$userId] = new $this->userClass($userId, $user);
            }
            Session::set('security.ldap_users', $ldapUsers, true);
        }

        return Session::get('security.ldap_users', array(), true);
    }

    /**
     * @return array[]
     */
    public function getGroups()
    {
        if (!Session::get('security.ldap_groups')) {
            $security = Config::get()->security;
            $list = ldap_search($this->conn, $security->user_base_dn, 'objectclass=group');
            $entries = ldap_get_entries($this->conn, $list);
            unset($entries['count']);


            Session::set('security.ldap_groups', array());
            foreach ($entries as $entry) {
                if (isset($entry[$security->groupname_attribute])) {
                    $groupDn = $entry["distinguishedname"][0];
                    if ($groupDn) {
                        Session::set('security.ldap_groups.'.$groupDn, $this->getGroupUsers($groupDn));
                    }
                }
            }
        }
        return Session::get('security.ldap_groups');
    }

    /**
     * @param string $username
     * @return string[]
     */
    public function getUserGroups($username)
    {
        $entries = $this->search($username);
        unset($entries['count']);

        $result = array();
        if (!empty($entries)) {
            foreach ($entries[0]["memberof"] as $entry) {
                $result[] = $entry;
            }
        }
        return $result;
    }

    protected function search($username)
    {
        $security = Config::get()->security;
        $search = ldap_search($this->conn, $security->user_base_dn, $security->username_attribute . '=' . $username);
        $entries = ldap_get_entries($this->conn, $search);
        unset($entries['count']);
        return $entries;
    }

    protected function getGroupUsers($groupDn)
    {
        $security = Config::get()->security;
        $search = ldap_search($this->conn, $security->user_base_dn, '(&(objectCategory=User)(memberOf=' . $groupDn . '))');
        $entries = ldap_get_entries($this->conn, $search);
        unset($entries['count']);

        $result = array();
        foreach ($entries as $entry) {
            $result[] = $entry[$security->username_attribute][0];
        }
        return $result;
    }
}
