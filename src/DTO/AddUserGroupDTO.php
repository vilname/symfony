<?php


namespace App\DTO;


class AddUserGroupDTO
{
    private array $payload;

    public function __construct(string $page, string $perPage)
    {
        $this->payload = ['page' => $page, 'perPage' => $perPage];
    }

    public function toAMQPMessage(): string
    {
        return json_encode($this->payload);
    }
}