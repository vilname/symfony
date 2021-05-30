<?php


namespace App\Security;


use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser implements UserInterface
{
    private string $userName;
    private array $roles;

    public function __construct(array $credentials)
    {
        $this->userName = $credentials['username'];
        $this->roles = array_unique(array_merge($credentials['roles'] ?? [], ['ROLE_ADMIN']));
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return '';
    }

    public function getSalt()
    {
        return '';
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function eraseCredentials()
    {

    }
}
