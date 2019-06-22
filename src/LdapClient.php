<?php

declare(strict_types=1);

namespace flujan\LdapClient;

class LdapClient
{
    private $connection;
    private $result;

    /**
     * @param string $server
     * @param int $port
     * @param string $user
     * @param string $password
     * @param array $options
     * @return bool
     */
    public function connection(string $server, int $port, string $user, string $password, array $options = []): bool
    {
        $this->connection = ldap_connect($server, $port);
        foreach ($options as $key => $option) {
            ldap_set_option($this->connection, $key, $option);
        }
        return @ldap_bind($this->connection, $user, $password);
    }

    /**
     * @param string $baseDn
     * @param string $searchFilter
     * @return LdapClient
     */
    public function search(string $baseDn, string $searchFilter): self
    {
        $this->result = @ldap_search($this->connection, $baseDn, $searchFilter);
        return $this;
    }

    /**
     * @return array
     */
    public function getEntries(): array
    {
        return $this->result ? @ldap_get_entries($this->connection, $this->result) : [];
    }

    /**
     * @return bool
     */
    public function disconnection(): bool
    {
        return ($this->connection) ? @ldap_unbind($this->connection) : false;
    }
}