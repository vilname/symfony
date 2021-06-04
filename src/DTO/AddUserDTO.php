<?php


namespace App\DTO;


class AddUserDTO
{
    private array $payload;

    public function __construct(int $groupId, string $groupName, int $count)
    {
        $this->payload = ['groupId' => $groupId, 'groupName' => $groupName, 'count' => $count];
    }

    public function toAMQPMessage(): string
    {
        return json_encode($this->payload);
    }
}