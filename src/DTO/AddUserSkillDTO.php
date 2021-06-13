<?php


namespace App\DTO;


class AddUserSkillDTO
{
    private array $payload;

    public function __construct(int $groupId, string $userName, int $count = null)
    {
        $this->payload = ['userId' => $groupId, 'userName' => $userName, 'count' => $count];
    }

    public function toAMQPMessage(): string
    {
        return json_encode($this->payload);
    }
}