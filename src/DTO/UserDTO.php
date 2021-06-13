<?php


namespace App\DTO;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=32)
     */
    public string $login;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=32)
     */
    public string $password;

    public array $roles;

    public array $newUserSkilll;

    public array $skillSelect;

    /**
     * @throws \JsonException
     */
    public function __construct(array $data)
    {
        $this->login = $data['login'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->roles = json_decode($data['roles'], true, 512, JSON_THROW_ON_ERROR) ?? [];
        $this->newUserSkill = $data['newUserSkill'] ?? [];
        $this->skillSelect = $data['skillSelect'] ?? [];
    }

    public static function fromEntity(User $user): self
    {
        return new self([
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'roles' => $user->getRoles()
        ]);
    }
}