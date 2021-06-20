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

    public ?int $groupId; // пишу максимально подходящую группы пользователю

    public string $message; // пишу сообщение

    /**
     * @throws \JsonException
     */
    public function __construct(array $data)
    {
        if (is_string($data['roles'])) {
            $data['roles'] = json_encode([$data['roles']]);
        } else {
            $data['roles'] = json_encode([]);
        }

        $this->login = $data['login'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->roles = json_decode($data['roles'], true, 512, JSON_THROW_ON_ERROR) ?? [];
        $this->newUserSkill = $data['newUserSkill'] ?? [];
        $this->skillSelect = $data['skillSelect'] ?? [];
        $this->groupId = $data['groupId'] ?? 0;
    }

    public static function fromEntity(User $user): self
    {
        return new self([
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'roles' => $user->getRoles(),
            'groupId' => isset($user->groupId) ? $user->groupId : 0,
            'message' => isset($user->message) ? $user->message : ''
        ]);
    }
}