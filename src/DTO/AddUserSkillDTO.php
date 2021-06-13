<?php


namespace App\DTO;


class AddUserSkillDTO
{
    private array $payload;

    public function __construct(string $userName, int $count = null)
    {
        $this->payload = ['userName' => $userName, 'count' => $count];
    }

    public function toAMQPMessage(): string
    {
        return json_encode($this->payload);
    }
}