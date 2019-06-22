<?php
declare(strict_types=1);

namespace flujan\LdapClient;

use PHPUnit\Framework\TestCase;


class LdapClientTest extends TestCase
{
    private $options = [ LDAP_OPT_PROTOCOL_VERSION => 3, LDAP_OPT_REFERRALS => 0];
    private $user = 'cn=read-only-admin,dc=example,dc=com';
    private $password = 'password';
    private $port = 389;
    private $server = 'ldap.forumsys.com';
    private $baseDn = 'dc=example,dc=com';
    private $filter = 'uid=riemann';


    public function testConnectionSuccess()
    {
        $ldap  = new LdapClient();
        $connection =$ldap->connection($this->server, $this->port, $this->user, $this->password, $this->options);
        $this->assertTrue($connection);

    }

    public function testConnectionFail()
    {
        $ldap  = new LdapClient();
        $connection =$ldap->connection($this->server, $this->port, $this->user, $this->password.'1', $this->options);
        $this->assertFalse($connection);

    }


    public function testSearchSuccess()
    {
        $ldap  = new LdapClient();
        $ldap->connection($this->server, $this->port, $this->user, $this->password, $this->options);
        $class = $ldap->search($this->baseDn, $this->filter);
        $this->assertInstanceOf(LdapClient::class, $class);
    }


    public function testGetEntitiesSuccess()
    {
        $ldap  = new LdapClient();
        $ldap->connection($this->server, $this->port, $this->user, $this->password, $this->options);
        $entries = $ldap->search($this->baseDn, $this->filter)->getEntries();
        $this->assertIsArray($entries);
        $this->assertArrayHasKey('count', $entries);
    }


    public function testGetEntitiesFail()
    {
        $ldap  = new LdapClient();
        $ldap->connection($this->server, $this->port, $this->user, $this->password, $this->options);
        $entries = $ldap->search($this->baseDn.'1', $this->filter)->getEntries();
        $this->assertIsArray($entries);
        $this->assertArrayNotHasKey('count', $entries);
    }


    public function testDisconnectSuccess()
    {
        $ldap  = new LdapClient();
        $ldap->connection($this->server, $this->port, $this->user, $this->password, $this->options);
        $ldap->search($this->baseDn, $this->filter);
        $disconnection = $ldap->disconnection();
        $this->assertTrue($disconnection);
    }

    public function testDisconnectFail()
    {
        $ldap  = new LdapClient();
        $disconnection = $ldap->disconnection();
        $this->assertFalse($disconnection);
    }

}