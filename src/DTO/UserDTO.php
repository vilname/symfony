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

    public array $teacherSkill;

    public int $skillSelect;

    /**
     * @throws \JsonException
     */
    public function __construct(array $data)
    {
        dump($data);

        $this->login = $data['login'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->roles = json_decode($data['roles'], true, 512, JSON_THROW_ON_ERROR) ?? [];
        $this->teacherSkill = $data['teacherSkill'] ?? [];
        $this->skillSelect = $data['skillSelect'] ?? 0;
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