<?php


namespace App\Consumer\AddAppertice\Input;

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
    private string $groupName;

    /**
     * @Assert\Type("numeric")
     */
    private int $count;

    public static function createFromQueue(string $messageBody): self{
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->groupId = $message['groupId'];
        $result->groupName = $message['groupName'];
        $result->count = $message['count'];

        return $result;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
