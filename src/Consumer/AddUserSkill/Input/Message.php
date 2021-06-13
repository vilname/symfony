<?php


namespace App\Consumer\AddUserSkill\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class Message
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max="32")
     */
    private string $userName;

    /**
     * @Assert\Type("numeric")
     */
    private int $count;

    public static function createFromQueue(string $messageBody): self{
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);

        $result = new self();
        $result->userName = $message['userName'];
        $result->count = $message['count'];

        return $result;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
