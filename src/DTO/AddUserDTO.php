<?php


namespace App\DTO;


class AddUserDTO
{
    private array $payload;

    public function __construct(int $groupId, string $userName, int $count)
    {
        $this->payload = ['groupId' => $groupId, 'userName' => $userName, 'count' => $count];
    }

    public function toAMQPMessage(): string
    {
        return json_encode($this->payload);
    }
}