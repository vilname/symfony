<?php


namespace App\Consumer\AddUser\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class Message
{
    /**
     * @Assert\Type("numeric")
     */
    private int $groupId;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max="32")
     */
    private string $userLogin;

    /**
     * @Assert\Type("numeric")
     */
    private int $count;

    public static function createFromQueue(string $messageBody): self{
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->groupId = $message['groupId'];
        $result->userLogin = $message['userLogin'];
        $result->count = $message['count'];

        return $result;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function getUserName(): string
    {
        return $this->userLogin;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
