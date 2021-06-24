<?php


namespace App\Consumer\AddUserGroup\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class Message
{
    /**
     * @Assert\Type("numeric")
     */
    private int $page;

    /**
     * @Assert\Type("numeric")
     */
    private string $perPage;


    public static function createFromQueue(string $messageBody): self{
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);

        $result = new self();
        $result->page = $message['page'];
        $result->perPage = $message['perPage'];

        return $result;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getPerPage(): string
    {
        return $this->perPage;
    }


}
